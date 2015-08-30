<?php

class IndexCommand extends CConsoleCommand {

    public function run($args) {
        $action = $args[0];
        if ($action == 'request') {
            return $this->removeRequest();
        }
    }

    private function removeRequest() {                        
        $query = "DELETE FROM {{request}} WHERE (UNIX_TIMESTAMP() - `timestamp`) > 86400";
        $row = Yii::app()->db_device->createCommand($query)->execute();
        printf("So ban ghi bi xoa di: %d\n", $row);
        
        //xoa bang device_updated_xml
        $query = "DELETE FROM {{device_updated_xml}} WHERE (UNIX_TIMESTAMP() - `timestamp`) > 86400";
        $row = Yii::app()->db_device->createCommand($query)->execute();
        printf("So ban ghi bi xoa di: %d\n", $row);
    }
    
}
