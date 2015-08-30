<?php

class PushCommand extends CConsoleCommand {
    
    public function run($args) {
        $action = $args[0];
        if ($action == 'ios') {
            $this->PushIOS();
        } elseif ($action == 'android') {
            $this->PushAndroid();
        } elseif ($action == 'test') {
            $this->PushAndroidTest();
        } elseif($action == 'testios') {
            $this->testIos();
        }
    }

    private function getAppInfo($app_id) {
        $query = "SELECT api_access_key, pem_file FROM {{list_app}} WHERE id = " . $app_id;
        $db = EduDataBase::getConnection('db_global');
        return $db->createCommand($query)->queryRow();
    }


    /*
     * command
     * _SV=vn php /home/ubuntu/edu_php_backend/crontab.php push ios
     */

    private function PushIOS() {
        $os_type = 1;
        $db = EduDataBase::getConnection('db_global');
        $query = "SELECT * FROM {{push_queue}} WHERE is_push = 0 AND os = " . $os_type;
        $row = $db->createCommand($query)->queryRow();
        if (empty($row)) {
            die("Da het push queue\n");
        }
        $push_queue_id = $row['id'];
        
        // Put your device token here (without spaces):
        $deviceToken = $row['device_token'];
        
        // Put your private key's passphrase here:            
        $passphrase = 'Edu123';
        // Put your alert message here:
        $message = $row['message'];

        $ctx = stream_context_create();
        
        $appInfo = $this->getAppInfo($row['app_id']);
        $pemFile = CERTIFICATE_PATH . '/' . $appInfo['pem_file'];
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        //$apns_server = 'ssl://gateway.sandbox.push.apple.com:2195';
        $apns_server = 'ssl://gateway.push.apple.com:2195';
        $fp = stream_socket_client($apns_server, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        echo 'Connected to APNS' . PHP_EOL . '<br />';
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );        
        echo '<pre>' . print_r($body, true) . '</pre>';
        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
            echo '<br />Message not delivered' . PHP_EOL;
        else
            echo '<br />Message successfully delivered' . PHP_EOL;
        // Close the connection to the server
        fclose($fp);
        $query = "UPDATE {{push_queue}} SET is_push = 1 WHERE id = " . $push_queue_id;
        $db->createCommand($query)->execute();

        sleep(1);
        $args[0] = 'ios';
        $this->run($args);
    }

    private function testIos() {
        // Put your device token here (without spaces):
        //$deviceToken = '45e42127a4c363e9a9987767efe640e93becb1d31931d62a860d11eb4c992fea';
        $deviceToken = '0529a415491ac4ec4fc6fdd1e21f930d0c516965b0144aa14983438115546e4e';
        
        // Put your private key's passphrase here:            
        $passphrase = 'Edu123';
        // Put your alert message here:
        $message = 'Test push ios - ' . date('d/m/Y H:i:s');

        $ctx = stream_context_create();

        $pemFile = ROOT_PATH . '/lib/certificate/shape_dev.pem';
        //$pemFile = CERTIFICATE_PATH . '/1418010105_edu_production.pem';
        
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $apns_server = 'ssl://gateway.sandbox.push.apple.com:2195';
        //$apns_server = 'ssl://gateway.push.apple.com:2195';
        $fp = stream_socket_client($apns_server, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        echo 'Connected to APNS' . PHP_EOL . '<br />';
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );
        echo '<pre>' . print_r($body, true) . '</pre>';
        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
            echo '<br />Message not delivered' . PHP_EOL;
        else
            echo '<br />Message successfully delivered' . PHP_EOL;
        // Close the connection to the server
        fclose($fp);
    }

    
    /*
     * command
     * _SV=vn php /home/ubuntu/edu_php_backend/crontab.php push android
     */

    private function PushAndroid() {
        $os_type = 2;
        $db = EduDataBase::getConnection('db_global');
        $query = "SELECT * FROM {{push_queue}} WHERE is_push = 0 AND os = " . $os_type;
        $row = $db->createCommand($query)->queryRow();
        if (empty($row)) {
            die("Da het push queue\n");
        }
        $push_queue_id = $row['id'];

        $registrationIds = array($row['device_token']);

        $msg = array(
            'payload' => array(
                'aps' => array(
                    'body' => 'Edu push',
                    'alert' => $row['message']
                ),
                'some_extra_key' => '+1'
            )
        );
        $fields = array
            (
            'registration_ids' => $registrationIds,
            'data' => $msg
        );

        $appInfo = $this->getAppInfo($row['app_id']);        
        $headers = array
            (
            'Authorization: key=' . $appInfo['api_access_key'],
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        var_dump($result);
        echo "\n";

        $info = json_decode($result, true);

        if ($info['failure'] == 1) {
            $query = "UPDATE {{push}} SET uninstall = 1 WHERE device_token = :device_token";
            $values = array(':device_token' => $row['device_token']);
            $db_device = EduDataBase::getConnection('db_device');
            $db_device->createCommand($query)->bindValues($values)->execute();
        }

        $query = "UPDATE {{push_queue}} SET is_push = 1 WHERE id = " . $push_queue_id;
        $db->createCommand($query)->execute();

        sleep(1);
        $args[0] = 'android';
        $this->run($args);
    }

    private function PushAndroidTest() {

        $registrationIds = array();
        //SOn
                        
        //word machine
        $key = 'AIzaSyBU8cbHuOwE4EldO2fdzekNcGovUBqBUsE';
        $registrationIds[] = 'APA91bEF-m-Vj-y2dWwUCZBr--MzlvCJMfP2GsKgHIuzafyRjoHFCaxkoy5AGCS256Eb1JM_jR-5fthoQMyXvqqsZwZgoxNsIrvncPmjgMUsSxbesIOH5a3Vqc3EGUgbQTczjKPTMs55uSfaYgDnWI9HrrAjn9SrOOxRknYJ69tGHSMLhdvFZd8';       
        
        
        

        $msg = array(
            'payload' => array(
                'aps' => array(
                    'body' => 'Monkey Junior',
                    'alert' => 'Phonics app for your kids',
                ),
                'some_extra_key' => '+1'
            )
        );
        $fields = array(
            'data' => $msg,
            'registration_ids' => $registrationIds
        );
        
        
        
        $headers = array
            (
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );
        echo '<pre>' . print_r($fields, true) . '</pre>';
        echo '<pre>' . print_r($headers, true) . '</pre>';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        //$info = json_decode($result, true);
        var_dump($result);
    }

}
