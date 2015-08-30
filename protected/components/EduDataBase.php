<?php

class EduDataBase {

    /**
     * @param string $db
     * @return CDbConnection
     */
    public static function getConnection($db = 'db') {
        $connect = Yii::app()->$db;        
        return $connect;
    }

    /**
     * 
     * @param CDbConnection $db
     * @param string $key
     * @param string $query
     */
    public static function fetchWithKey($db, $key = '', $query) {
        $data = array();
        $list = $db->createCommand($query)->queryAll();
        if (!empty($list)) {
            foreach ($list as $item) {
                $data[$item[$key]] = $item;
            }
        }
        return $data;
    }

}
