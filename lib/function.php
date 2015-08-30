<?php

define('VARIABLE_STRING', 1);
define('VARIABLE_NUMBER', 2);
define('VARIABLE_FLOAT', 3);
define('VARIABLE_ARRAY', 4);

function base_url()
{
    return Yii::app()->baseUrl;
}

/**
 * //lay gia tri tren url
 * @param $name
 * @param int $variable_type
 * 1: string
 * 2: number
 * 3: float
 */
function urlGETParams($name, $variable_type = 1)
{
    $params = $_GET;
    $vl = $params[$name];
    if($variable_type == VARIABLE_NUMBER) {
        $vl = intval($vl);
    }elseif($variable_type == VARIABLE_FLOAT) {
        $vl = floatval($vl);
    }else {
        $vl = trim($vl);
    }
    return $vl;
}

/**
 * //lay gia tri tren url
 * @param $name
 * @param int $variable_type
 * 1: string
 * 2: number
 * 3: float
 * 4: array
 */
function formPostParams($name, $variable_type = 1)
{

    $params = $_POST;
    $vl = $params[$name];
    if($variable_type == VARIABLE_NUMBER) {
        $vl = intval($vl);
    }elseif($variable_type == VARIABLE_FLOAT) {
        $vl = floatval($vl);
    }elseif($variable_type == VARIABLE_ARRAY) {
        $vl = array_filter($vl);
    }else {
        $vl = trim($vl);
    }
    return $vl;
}

/**
 * @param type $msg
 * array | string
 * @param type $type
 * danger | success | info | warning
 */
function createMessage($msg, $type = 'success')
{
    Yii::app()->session['msg'] = $msg;
    Yii::app()->session['msg_type'] = $type;
}

function showMessage()
{
    $html = '';
    $msg = Yii::app()->session['msg'];
    if (!empty($msg)) {
        $type = Yii::app()->session['msg_type'];
        $html .= '<div class="portlet-body"><div class="alert alert-block alert-' . $type . ' fade in">';
        $html .= '<button type="button" class="close" data-dismiss="alert"></button>';

        $arr_heading = array(
            'danger' => 'Error!',
            'success' => 'Success!',
            'info' => 'Info!',
            'warning' => 'Warning!'
        );
        $html .= '<h4 class="alert-heading">' . $arr_heading[$type] . '</h4>';

        $html .= '<p>';
        if (is_array($msg)) {
            foreach ($msg as $m) {
                $html .= $msg . '<br />';
            }
        } else {
            $html .= $msg;
        }
        $html .= '</p>';
        $html .= '</div></div>';
        unset(Yii::app()->session['msg']);
        unset(Yii::app()->session['msg_type']);
    }
    return $html;
}

function yii_insert_row($table, $params, $db = 'db', $replace = false)
{
    if (empty($table)) {
        throw new Exception('Vui long nhap ten bang');
    }
    if (empty($params)) {
        throw new Exception('Vui long nhap du lieu de insert');
    }
    $fields = array_keys($params);
    $query = "{{" . $table . "}} (`" . implode('`,`', $fields) . "`) VALUES ";
    if ($replace == true) {
        $query = "REPLACE INTO " . $query;
    } else {
        $query = "INSERT IGNORE INTO " . $query;
    }

    $i = 1;
    $query .= "(";
    foreach ($fields as $field) {
        $query .= ":" . $field . ",";
    }
    $query = trim($query, ',');
    $query .= ")";
    return Yii::app()->$db->createCommand($query)->bindValues($params)->execute();
}

function yii_update_row($table, $params, $condition = '1', $db = 'db')
{
    if (empty($table)) {
        throw new Exception('Vui long nhap ten bang');
    }
    if (empty($params)) {
        throw new Exception('Vui long nhap du lieu de thuc thi cau lenh update');
    }
    $fields = array_keys($params);
    $query = "UPDATE {{" . $table . "}} SET ";
    $tags = array();
    foreach ($fields as $field) {
        $tags[] = '`' . $field . '`' . ' = :' . $field;
    }
    $query .= implode(', ', $tags) . " WHERE " . $condition;
    $database = EduDataBase::getConnection($db);
    $values = array();
    foreach ($params as $key => $vl) {
        $values[':' . $key] = trim($vl);
    }
    return $database->createCommand($query)->bindValues($values)->execute();
}

/**
 * $params = array(
 * 'fields' => array('name', 'age'),
 * 'values' => array(
 * array('name' => 'Ha Anh Man', 'age' => 24),
 * array('name' => 'Ha Anh Man 2', 'age' => 25)
 * )
 * );
 * @param type $table
 * @param type $params
 * @param type $db
 * @throws Exception
 */
function yii_insert_multiple($table, $params = array(), $db = 'db', $replace = false)
{
    if (empty($table)) {
        throw new Exception('Vui long nhap ten bang');
    }
    if (empty($params)) {
        throw new Exception('Vui long nhap du lieu de insert');
    }
    $fields = array_keys($params[0]);
    $count = count($params);
    $query = "{{" . $table . "}} (`" . implode('`,`', $fields) . "`) VALUES ";
    if ($replace == true) {
        $query = "REPLACE INTO " . $query;
    } else {
        $query = "INSERT IGNORE INTO " . $query;
    }

    for ($i = 0; $i < $count; $i++) {
        $query .= "(";
        foreach ($fields as $field) {
            $query .= ":" . $field . "_" . $i . ",";
        }
        $query = trim($query, ',');
        $query .= "),";
    }
    $query = trim($query, ',');
    $command = Yii::app()->$db->createCommand($query);
    for ($i = 0; $i < $count; $i++) {
        foreach ($fields as $field) {
            $command->bindParam(':' . $field . '_' . $i, $params[$i][$field]);
        }
    }
    return $command->execute();
}

function vietdecode($value)
{
    $value = trim($value);
    $value = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $value);
    $value = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $value);
    $value = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $value);
    $value = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $value);
    $value = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $value);
    $value = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $value);
    $value = preg_replace("/(đ)/", 'd', $value);
    $value = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $value);
    $value = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $value);
    $value = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $value);
    $value = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $value);
    $value = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $value);
    $value = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $value);
    $value = preg_replace("/(Đ)/", 'D', $value);

    /**
     *   : = %3a   / = %2f   @ = %40
     *   + = %2b   ( = %28   ) = %29
     *   ? = %3f   = = %3d   & = %26
     */
    $trans = array(
        ':' => '', '/' => '', '@' => '', '+' => '', '(' => '', ')' => '', '?' => '', '=' => '', '&' => ''
    );
    $value = strtr($value, $trans);

    $value = trim($value, '-');
    return strtolower($value);
}

/**
 * function clean URL
 * return string url chi co cac ky tu tu a-z, 0-9 va khong chap nhan cac ky tu dac biet
 */
function change_url_seo($string, $file = false)
{
    $value = vietdecode($string);
    if ($file == false) {
        $value = preg_replace("/[^a-zA-Z0-9]/ism", '-', $value);
    } else {
        $value = preg_replace("/[^a-zA-Z0-9\.]/ism", '-', $value);
    }
    $value = preg_replace("/[\-]+/ism", '-', $value);
    if ($file == false) {
        preg_match_all('#[a-zA-Z0-9\s\-\+]+#im', $value, $matches);
    } else {
        preg_match_all('#[a-zA-Z0-9\s\-\+\.]+#im', $value, $matches);
    }
    $value = implode('', $matches[0]);
    $value = trim($value);
    $value = preg_replace('/\s+/', '-', $value);
    $value = preg_replace('/(\+)+/', '-', $value);
    $value = preg_replace('/(\-)+/', '-', $value);
    return trim($value, '-');
}

function general_character($length = 1)
{
    $character = 'qwertyuiopasdfghjklzxcvbnm1234567890';
    $character_length = strlen($character) - 1;
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $random = rand(0, $character_length);
        $str .= $character[$random];
    }
    return $str;
}

function create_folder($path, $sub)
{
    if (!is_dir($path)) {
        mkdir($path);
    }
    $sub_path = '';
    $strleng = strlen($sub);
    for ($i = 0; $i < $strleng; $i++) {
        $sub_path .= '/' . $sub[$i];
        if (!is_dir($path . $sub_path)) {
            mkdir($path . $sub_path);
        }
    }
    return $sub_path;
}

function create_sub_folder($path, $sub_folder)
{
    $sub_path = '';
    $arr = explode('/', trim($sub_folder, '/'));
    $strleng = count($arr);
    for ($i = 0; $i < $strleng; $i++) {
        $sub_path .= '/' . $arr[$i];
        if (!is_dir($path . $sub_path)) {
            mkdir($path . $sub_path);
        }
    }
    return $sub_path;
}

function copy_folder($source_folder, $dest_folder)
{
    try {
        if (!is_dir($dest_folder)) {
            mkdir($dest_folder);
        }

        $dir = new DirectoryIterator($source_folder);
        foreach ($dir as $fileinfo) {
            $file = $fileinfo->getFilename();
            if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                $source = $source_folder . '/' . $file;
                $dest = $dest_folder . '/' . $file;
                @copy($source, $dest);
            } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
                copy_folder($source_folder . '/' . $file, $dest_folder . '/' . $file);
            }
        }
    } catch (Exception $exc) {

    }
}

/**
 * kiem tra dinh dang file anh truoc khi upload
 *
 * @param string $pattern
 * @param string $filename
 * @param string $tmp_file
 * @return boolean @date 22/04/2012
 */
function image_match($filename, $tmp_file, $pattern = 'png|jpe?g|gif')
{
    $pattern = '/^.*\.(' . $pattern . ')$/is';
    if (preg_match($pattern, $filename)) {
        if (getimagesize($tmp_file)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * xoa thu muc
 * @param $dir
 */
function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function plist_create_file($structure, $dest)
{
    require_once(ROOT_PATH . '/lib/cfpropertylist/CFPropertyList.php');
    /*
     * create a new CFPropertyList instance without loading any content
     */
    $plist = new CFPropertyList();

    $td = new CFTypeDetector();
    $guessedStructure = $td->toCFType($structure);
    $plist->add($guessedStructure);
    $plist->saveXML($dest);
}

function plist_render($structure)
{
    if ($_GET['test'] == 1) {
        echo '<pre>' . print_r($structure, true) . '</pre>';
        return;
    }
    require_once(ROOT_PATH . '/lib/cfpropertylist/CFPropertyList.php');
    /*
     * create a new CFPropertyList instance without loading any content
     */
    $plist = new CFPropertyList();

    $td = new CFTypeDetector();
    $guessedStructure = $td->toCFType($structure);
    $plist->add($guessedStructure);
    echo $plist->toXML(true);
}


function convert_time($date, $granularity = 1)
{
    $retval = '';
    //return date('d/m/Y H:i:s', $date);
    //$date = strtotime($date);
    $difference = time() - $date;
    if ($difference <= 1) {
        return '1 second ago';
    }
    $periods = array('decade' => 315360000,
        'year' => 31536000,
        'month' => 2628000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1);

    if ($difference > $periods['month']) {
        return date('F, d, Y', $date);
    } else {
        foreach ($periods as $key => $value) {
            if ($difference >= $value) {
                $time = floor($difference / $value);
                $difference %= $value;
                $retval .= ($retval ? ' ' : '') . $time . ' ';
                $retval .= (($time > 1) ? $key . 's' : $key);
                $granularity--;
            }
            if ($granularity == '0') {
                break;
            }
        }
        return $retval . ' ago';
    }
}

/**
 * lay IP cua client
 * - hien server lom dung tam ham nay
 * - khi nao server hon thi dung ham cua drupal core: ip_address()
 * @staticvar string $ip_address
 * @return string
 */
function ip_address()
{
    $ip_address = NULL;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ip_address_parts as $ip) {
            if ($ip != '127.0.0.1') {
                $ip_address = $ip;
                return $ip;
            }
        }
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    return $ip_address;
}

function detectIpVN($ip)
{
    if ($ip == '127.0.0.1') {
        return true;
    }
    $parts = explode('.', $ip);
    foreach ($parts as $index => $sub) {
        $parts[$index] = str_pad($sub, 3, '0', STR_PAD_LEFT);
    }
    $ip = implode('', $parts);
    $query = "SELECT id FROM {{ip_vn}} WHERE range_start <= :ip AND range_end >= :ip";
    $id = Yii::app()->db_global->createCommand($query)->bindValues(array(':ip' => $ip))->queryScalar();
    return $id > 0;
}

/**
 *
 * send mail
 * @param string $from Email nguoi gui
 * @param string $to Email nguoi nhan
 * @param string $subject Tieu de mail
 * @param string $content Noi dung email (Chap nhan ca HTML)
 * @param string $fromName Ten nguoi gui (option)
 * @param int $server
 * @return boolean
 * @author anhmantk
 */
function send_mail($from = null, $to, $subject, $content, $cc = array(), $fromName = 'Edu system', $mail_server = 1, $attachment = '')
{
    if (empty($to)) {
        return -1;
    }

    require_once ROOT_PATH . '/lib/phpmailer/class.phpmailer.php';
    $server = array('email' => 'noreply.littlestar@gmail.com', 'password' => 'edu123!@#');
    if ($mail_server == 2) {
        $server = array('email' => 'noreply.earlystart@gmail.com', 'password' => 'EarlyStart2015');
    }

    if ($from == null) {
        $from = $server['email'];
    }
    if ($mail_server == 2) {
        $from = 'contact.earlystart@gmail.com';
    }

    $mail = new PHPMailer();
    // set charset cho noi dung mail
    $mail->CharSet = "UTF8";
    // noi dung mail
    $body = $content;
    // khoi tao send mail voi SMTP
    // khoi tao send mail voi SMTP
    $mail->IsSMTP();
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port = 465;                   // set the SMTP port for the GMAIL server        
    //$mail->SMTPDebug = true;


    $mail->Username = $server['email'];  // GMAIL username
    $mail->Password = $server['password'];            // GMAIL password
    // add Reply khi an vao Reply

    $mail->AddReplyTo($from, $fromName);
    if ($attachment != '' && file_exists($attachment)) {
        $mail->AddAttachment($attachment);
    }
    // Email nguoi gui
    $mail->From = $mail_server;
    // Ten nguoi gui
    $mail->FromName = $fromName;
    // Tieu de thu
    $mail->Subject = $subject;

    $mail->WordWrap = 50; // set word wrap
    // Noi dung mail co the de dang HTML
    $mail->MsgHTML($body);
    // email nguoi gui
    $mail->AddAddress($to);
    if (!empty($cc)) {
        foreach ($cc as $c) {
            $mail->AddCC($c);
        }
    }

    // Attachment
    //$mail->AddAttachment("images/phpmailer.gif");             // attachment
    // send as HTML
    $mail->IsHTML(true);


    if (!$mail->Send()) {
        if ($_GET['a'] == 1) {
            return "Mailer Error: " . $mail->ErrorInfo;
        }
        return false; // send error
    } else {
        return true; // send success
    }
}

/**
 * ramdon array ban dau
 * @param $list
 * @return array
 */
function random_array_function($list)
{
    $count = count($list);
    if (count($list) > 1) {
        $tmp = array();
        for ($i = 0; $i < $count; $i++) {
            $index = array_rand($list);
            $tmp[] = $list[$index];
            unset($list[$index]);
        }
        $list = $tmp;
    }
    return $list;
}

/**
 * scan file va thuc muc cua folder
 * @param $folder
 * @param $paths
 * @return array
 */
function scan_folder($folder, &$paths)
{
    if (!is_dir($folder)) {
        return array();
    }
    $dir = new DirectoryIterator($folder);
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot() && $fileinfo->isFile()) {
            $paths[] = $folder . '/' . $fileinfo->getFilename();
        } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
            $d = $fileinfo->getFilename();
            $fd = $folder . '/' . $d;
            scan_folder($fd, $paths);
        }
    }
}

function generateCouponCode($length = 5)
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $coupon = '';
    for ($i = 0; $i < $length; ++$i) {
        $random = str_shuffle($chars);
        $coupon .= $random[0];
    }

//    $query = "SELECT id FROM tbl_agent WHERE coupon = :coupon";
//    $exits = Yii::app()->db_agent->createCommand($query)->bindValues(array(':coupon' => $coupon))->queryScalar();
//    if (!empty($exits)) {
//        generateCouponCode();
//    }
    return $coupon;
}

/**
 * Registers a javascript file.
 * @param string $url URL of the javascript file
 * @param integer $position the position of the JavaScript code. Valid values include the following:
 * <ul>
 * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
 * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
 * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
 * </ul>
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function register_script_file($listFile = array(), $position = CClientScript::POS_END)
{
    if (!empty($listFile)) {
        foreach ($listFile as $js) {
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/' . $js, $position);
        }
    }
}

/**
 * Registers a CSS file
 * @param string $url URL of the CSS file
 * @param string $media media that the CSS file should be applied to. If empty, it means all media types.
 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
 */
function register_css_file($listFile = array(), $media = '')
{
    if (!empty($listFile)) {
        foreach ($listFile as $css) {
            Yii::app()->clientScript->registerCSSFile(Yii::app()->request->baseUrl . '/' . $css, $media);
        }
    }
}

function strpos_all($haystack, $str)
{

    $kytusautext = array(' ', '; ', ', ', '. ', ': ');
    $allpos = array();

    $haystack = ' ' . trim($haystack) . ' ';
    foreach ($kytusautext as $char) {
        $needle = ' ' . trim($str) . $char;
        $offset = 0;

        while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
            $offset = $pos + 1;
            $allpos[] = $pos;
        }
    }
    return $allpos;
}