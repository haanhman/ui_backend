<?php

require_once ROOT_PATH . '/lib/simple_html_dom.php';

class VideoCommand extends CConsoleCommand {

    public function run($args) {
        $action = $args[0];
        if ($action == 'download') {
            $this->saveVideo($args);
        } elseif ($action == 'search') {
            $this->searchVideo($args);
        }
    }

    /*
     * command
     * php /home/ubuntu/edu_php_backend/crontab.php video download
     */

    private function saveVideo($args) {
        $db2 = EduDataBase::getConnection('db2');
        $query = "SELECT * FROM {{video}} WHERE downloaded = 0 AND extension <> 'youtube' AND extension <> 'vimeo' LIMIT 1";
        $row = $db2->createCommand($query)->queryRow();
        if (empty($row)) {
//            global $list_email_notification;
//            $to = array_shift($list_email_notification);
//            $content = 'Đã lấy hết video rồi, vui lòng vào server tắt crontab đi';
//            send_mail(null, $to, '[Edu] - Lấy hết video rồi', $content, $list_email_notification);
            echo "Het du lieu roi\n";
            die;
        }
        $video_url = $row['video_url'];
        $arr = explode('/', $video_url);
        $saveFile = $row['id'] . '.' . $row['extension'];

        $prefix = general_character(1);
        $savePath = VIDEO_THAM_KHAO . '/' . $prefix;
        if (!is_dir(VIDEO_THAM_KHAO)) {
            mkdir(VIDEO_THAM_KHAO);
        }
        if (!is_dir($savePath)) {
            mkdir($savePath);
        }

        $saveTo = $savePath . '/' . $saveFile;
        echo "FROM URL: " . $video_url . "\n";
        echo "SAVE TO:" . $saveTo . "\n";

        $command = "wget -O " . $saveTo . " " . $row['video_url'];
        exec($command);

        $video_url = $prefix . '/' . $saveFile;
        //neu co thumbnail
        $thumbnail = $row['video_thumb'];
        if ($row['video_thumb']) {
            $video_thumbnail = $row['video_thumb'];
            $arr = explode('.', $video_thumbnail);
            $thumbnail = $row['id'] . '.' . array_pop($arr);
            $thumbnailPath = $savePath . '/' . $thumbnail;
            $command = "wget -O " . $thumbnailPath . " " . $row['video_thumb'];
            exec($command);
            $thumbnail = $prefix . '/' . $thumbnail;
        }


        $query = "UPDATE {{video}} SET video_url = :video_url, video_thumb = :video_thumb, video_url_bak = :video_url_bak, video_thumb_bak = :video_thumb_bak, downloaded = 1 WHERE id = :id";
        $values = array(
            ':video_url' => $video_url,
            ':video_thumb' => $thumbnail,
            ':video_thumb_bak' => $row['video_thumb'],
            ':video_url_bak' => $row['video_url'],
            ':id' => $row['id']
        );
        $db2->createCommand($query)->bindValues($values)->execute();
        echo "Download video thanh cong\n";
        echo "ID: " . $row['id'] . "\n\n";
    }

    /*
     * command
     * php crontab.php video search asc
     */

    private function searchVideo($args) {

        //ini_set('memory_limit', '-1');
        $db2 = EduDataBase::getConnection('db2');
        if (isset($args[1])) {
            $query = "SELECT * FROM {{video_search}} WHERE is_run = 0 ORDER BY id " . $args[1] . " LIMIT 1";
        } else {
            $query = "SELECT * FROM {{video_search}} WHERE is_run = 0 LIMIT 1";
        }
        $row = $db2->createCommand($query)->queryRow();
        if (empty($row)) {
            die('Het du lieu roi');
        }

        $query = "SELECT * FROM {{video_source}}";
        $list = $db2->createCommand($query)->queryAll();
        $list_source = array();
        $search_result = array();
        $total_source = 0;
        $count_run = 0;
        echo "Tim kiem video: " . $row['keywords'] . "\n";

        foreach ($list as $item) {
            //`video_search_id`, `source_id`, `html_data`            
            $result = $this->findVideo($item, $row);
            if ($result == false) {
                continue;
            }
            $have_data = $result['have_data'];
            unset($result['have_data']);
            yii_insert_row('source_html', $result, 'db2', true);

            if ($have_data == 1) {
                $total_source++;
            }
            $count_run++;
            $search_result[$item['id']] = $have_data;
        }

        //`search_result`, `total_source`, `is_run`
        $update = "UPDATE {{video_search}} SET count_run = :count_run, search_result = :search_result, total_source = :total_source, is_run = :is_run WHERE id = :id";
        $values = array(
            ':search_result' => json_encode($search_result),
            ':total_source' => $total_source,
            ':is_run' => 1,
            ':count_run' => $count_run,
            ':id' => $row['id']
        );
        $db2->createCommand($update)->bindValues($values)->execute();
        echo "Done\n\n";

        sleep(1);
        echo "------------------------------------------------\n";
        echo "Chay them lan nua\n";
        echo "------------------------------------------------\n";
        $args[0] = 'search';
        $this->run($args);
    }

    private function findVideo($source, $key_input) {
        $search_url = str_replace('__key__', urlencode($key_input['keywords']), $source['search_url']);
        $domain = $source['domain'];
        echo $domain . "\n";
        echo "Search URL: " . $search_url . "\n";
        // Create DOM from URL or file

        $html = file_get_html($search_url);
        if ($html == false) {
            return false;
        }
        $have_data = 1;
        if ($domain == 'stockfootageforfree.com') {
            $find = $html->find('div.has-post-thumbnail');
            if (count($find) == 0) {
                $have_data = 0;
            }
        } elseif ($domain == 'bottledvideo.com') {
            $find = $html->find('#flow_body p', 0);
            $text = trim($find->plaintext);
            if ($text == 'Not Found') {
                $have_data = 0;
            }
        } elseif ($domain == 'videvo.net') {
            $find = $html->find('h1', 0);
            $text = trim($find->plaintext);
            if ($text == 'Sorry! No video found...') {
                $have_data = 0;
            }
        } elseif ($domain == 'free-hd-footage.com') {
            $find = $html->find('#videos p', 0);
            $text = trim($find->plaintext);
            if ($text == 'Sorry. No articles were found that match your criteria.') {
                $have_data = 0;
            }
        } elseif ($domain == 'vimeo.com') {
            $find = $html->find('h3.empty', 0);
            $text = trim($find->plaintext);
            if ($text == 'Sorry, no videos found') {
                $have_data = 0;
            }
        } elseif ($domain == 'videoblocks.com') {
            $find = $html->find('div.no-results', 0);
            $text = trim($find->plaintext);
            if ($text == 'No content matched your search criteria.') {
                $have_data = 0;
            }
        } elseif ($domain == 'videezy.com') {
            $find = $html->find('#no_results strong', 0);
            $text = trim($find->plaintext);
            if ($text == 'No results were found.') {
                $have_data = 0;
            }
        }

        echo $have_data . "\n";

        $result = array(
            'video_search_id' => $key_input['id'],
            'source_id' => $source['id'],
            'html_data' => $html->outertext,
            'have_data' => $have_data
        );
        return $result;
    }

}
