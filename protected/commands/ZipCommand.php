<?php

class ZipCommand extends CConsoleCommand {

    public function run($args) {
        $level_id = isset($args[1]) ? intval($args[1]) : 0;
        if ($args[0] == 'level') {
            if ($level_id == 0) {
                echo "Vui long nhap level ID\n";
                die;
            }
            $this->zipCard($level_id);
        } elseif ($args[0] == 'card') {        	
            if (!empty($args[1])) {
                $level_card = array();
                $arr = explode('-', $args[1]);               
                foreach ($arr as $item) {
                    $part = explode(':', $item);
                    $level_card[$part[0]] = explode(',', $part[1]);
                }
                if (empty($level_card)) {
                    echo "Vui long nhan danh sach ID card duoc thay doi\n";
                    return;
                }
                $file_zip = PRIVATE_TMP_PATH . '/card_upadte.zip';
                $this->createZipFile($file_zip, $level_card);
            } else {
                echo "Vui long nhan danh sach ID card duoc thay doi\n";
            }
        } elseif ($args[0] == 'language') {
            if (!empty($args[1])) {
                $this->zipLanguageData($args[1]);
            } else {
                echo "Vui long nhap language id\n";
            }
        }
    }

    private function zipCard($level_id) {
        $db = EduDataBase::getConnection();
        $query = "SELECT * FROM {{level_language}} WHERE id = " . $level_id;
        $level = $db->createCommand($query)->queryRow();
        if (empty($level)) {
            echo "Level " . $level_id . " khong ton tai\n";
            die;
        }

        $lang_id = $level['lang_id'];

        $query = "SELECT id, thumbnail, cate_id FROM {{level_category}} WHERE level_id = " . $level_id;
        $list = $db->createCommand($query)->queryAll();
        if (empty($list)) {
            echo "Du lieu khong ton tai\n";
            die;
        }
        $list_thumbnail = array();
        $list_cate_id = array();
        foreach ($list as $item) {
            $level_category_id[] = $item['id'];
            $thumbnail = json_decode($item['thumbnail'], true);
            if (!empty($thumbnail)) {
                foreach ($thumbnail as $type => $file) {
                    $list_thumbnail[] = 'resources-' . $type . '/' . $file;
                    //file anh lock
                    $file = str_replace('.png', '_lock.png', $file);
                    $list_thumbnail[] = 'resources-' . $type . '/' . $file;
                }
            }
            $list_cate_id[] = $item['cate_id'];
        }

        //copy anh 1_phone.png, 1_phone_lock.png
        //copy audio cua danh muc
        $category_audio = array();
        $query = "SELECT id_text FROM {{category_language}} WHERE cate_id IN (" . implode(',', $list_cate_id) . ") AND lang_id = " . $lang_id;
        $list_text_id = $db->createCommand($query)->queryColumn();
        if (!empty($list_text_id)) {
            $query = "SELECT audio_file FROM {{audio}} WHERE id_sentence IN (" . implode(',', $list_text_id) . ")";
            $category_audio = $db->createCommand($query)->queryColumn();
        }

        $query = "SELECT card_id FROM {{category_card}} WHERE lv_cate_id IN (" . implode(',', $level_category_id) . ")";
        $list_card_id = $db->createCommand($query)->queryColumn();
        if (empty($list_card_id)) {
            echo "Khong co card nao\n";
            die;
        }

        $query = "SELECT id FROM {{card_language}} WHERE id_card IN (" . implode(',', $list_card_id) . ") AND lang_id = " . $lang_id;
        $list_card = $db->createCommand($query)->queryColumn();
        if (empty($list_card)) {
            echo "Khong tim thay card id\n";
            die;
        }

        //kiem tra neu co file cu thi xoa di
        $zip_filename = 'level_' . $level_id . '.zip';
        $file_zip = PRIVATE_TMP_PATH . '/' . $zip_filename;
        if (file_exists($file_zip)) {
            @unlink($file_zip);
        }

        echo "dang tao file zip ...\n";
        $this->zipCategoryData($file_zip, $list_thumbnail, $category_audio);
        $level_card = array($level_id => $list_card);
        $this->createZipFile($file_zip, $level_card);
    }

    private function zipCategoryData($file_zip, $list_thumbnail = array(), $category_audio = array()) {
        set_time_limit(300);

        $zip = new ZipArchive();
        $zip->open($file_zip, ZipArchive::CREATE);
        if (!empty($category_audio)) {
            foreach ($category_audio as $file) {
                $audio_file = get_audio_path($file, true);
                $zip->addFile($audio_file, 'category/audio/' . $file);

                //copy audio android
                $audio_file = get_audio_android($file, true);
                $file = str_replace('.mp3', ANDROID_PREFIX, $file);
                $zip->addFile($audio_file, 'category/audio_android/' . $file);
            }
        }
        if (!empty($list_thumbnail)) {
            foreach ($list_thumbnail as $file) {
                $audio_file = PUBLIC_IMG_CATEGORY . '/' . $file;
                $zip->addFile($audio_file, 'category/image/' . $file);
            }
        }
        echo "Copy xong category data\n";
    }

    private function createZipFile($file_zip, $listCardLang) {
        if(file_exists($file_zip)) {
            unlink($file_zip);
        }
        set_time_limit(300);
        $listFolder = array();

        if (empty($listCardLang)) {
            return;
        }

        foreach ($listCardLang as $level_id => $listcard) {
            $level_path = PRIVATE_CARD_PATH . '/' . $level_id;
            foreach ($listcard as $card_lang_id) {
                if (is_dir($level_path . '/' . $card_lang_id)) {
                    $listFolder[] = $level_path . '/' . $card_lang_id;
                }
            }
        }

        $paths = array();
        foreach ($listFolder as $folder) {
            $this->readFolder($folder, $paths, $folder . '/');
        }

        if (!empty($paths)) {
            $zip = new ZipArchive();
            $zip->open($file_zip, ZipArchive::CREATE);
            foreach ($paths as $p => $rows) {
                $arr = explode('/', trim($p, '/'));
                $lv_id = $arr[count($arr) - 2];
                $card_id = array_pop($arr);
                $p = implode('/', $arr) . '/';
                foreach ($rows as $file) {
                    $localname = str_replace($p, '', $file);
                    $zip->addFile($file, 'card/' . $lv_id . $localname);
                }
                //echo $p . $card_id . " - done\n";
            }
            $zip->close();
            echo "Tao file zip: " . $file_zip . " thanh cong\n";
        }
    }

    private function readFolder($folder, &$paths, $folder_root) {
        $dir = new DirectoryIterator($folder);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                $paths[$folder_root][] = $folder . '/' . $fileinfo->getFilename();
            } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $d = $fileinfo->getFilename();
                $fd = $folder . '/' . $d;
                $this->readFolder($fd, $paths, $folder_root);
            }
        }
    }

    private function zipLanguageData($lang_id) {
        $query = "SELECT * FROM {{languages}} WHERE id = " . $lang_id;
        $db_global = EduDataBase::getConnection('db_global');
        $language = $db_global->createCommand($query)->queryRow();
        if (empty($language)) {
            echo "Khong ton tai ngon ngu voi id = " . $lang_id . "\n";
            die;
        }
        $font_version = $language['font_version'];
        $audio_version = $language['audio_version'];

        //kiem tra neu co file cu thi xoa di
        $zip_filename = 'language_' . $lang_id . '.zip';
        $file_zip = PRIVATE_TMP_PATH . '/' . $zip_filename;
        if (file_exists($file_zip)) {
            @unlink($file_zip);
        }
        $zip = new ZipArchive();
        $zip->open($file_zip, ZipArchive::CREATE);

        //copy game audio

        $game_audio_path = GAME_AUDIO_PATH . '/' . $lang_id . '/' . $audio_version;
        if (is_dir($game_audio_path)) {
            $paths = array();
            $dir = new DirectoryIterator($game_audio_path);
            foreach ($dir as $fileinfo) {
                if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                    $file = $game_audio_path . '/' . $fileinfo->getFilename();
                    $key = str_replace(GAME_AUDIO_PATH . '/', '', $file);
                    $paths[$key] = $file;
                }
            }

            if (!empty($paths)) {
                foreach ($paths as $localname => $filename) {
                    $zip->addFile($filename, 'gameaudio/' . $localname);
                }
            }
            echo "Copy xong game audio\n";
        }

        $fonts_path = FONT_PATH . '/' . $lang_id . '/' . $font_version;
        if (is_dir($fonts_path)) {
            $paths = array();
            $dir = new DirectoryIterator($fonts_path);
            foreach ($dir as $fileinfo) {
                if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                    $file = $fonts_path . '/' . $fileinfo->getFilename();
                    $key = str_replace(FONT_PATH . '/', '', $file);
                    $paths[$key] = $file;
                }
            }
            if (!empty($paths)) {
                foreach ($paths as $localname => $filename) {
                    $zip->addFile($filename, 'fonts/' . $localname);
                }
            }
            echo "Copy xong fonts\n";
        }
        $zip->close();
        echo "Tao file zip: " . $file_zip . " thanh cong\n";
    }

}
