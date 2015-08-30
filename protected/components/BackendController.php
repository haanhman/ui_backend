<?php

class BackendController extends CController
{

    public static $permission;
    protected $_table;
    protected $user;

    /**
     *
     * @var CDbConnection
     */
    protected $db;

    public function init()
    {
        parent::init();

        $this->db = EduDataBase::getConnection();
        $this->user = Yii::app()->session['user'];
        if (empty($this->user)) {
            $query_string = '?dest=' . base64_encode($_SERVER['REQUEST_URI']);
            $redirect = '/backend/login/index' . $query_string;
            $this->redirect($redirect);
        }

        //chon ngon ngu cho site
//        $lang = 'vi';
//        Yii::app()->sourceLanguage = '00';
//        Yii::app()->language = $lang;
        Yii::import('application.models.backend.*');
        Yii::app()->setSystemViewPath(ROOT_PATH . '/protected/views/system');
    }

    /**
     * get row data
     * @param $row_id
     * @param $table_name
     * @return CDbDataReader|mixed
     */
    public function getRow($row_id, $table_name = '')
    {
        $table = !empty($table_name) ? $table_name : $this->_table;
        $query = "SELECT * FROM {{" . $table . "}} WHERE id = " . $row_id;
        return $this->db->createCommand($query)->queryRow();
    }

    protected function controllerIgnore()
    {
        return array(
            'backend' => array(
                'profile' => array(
                    'password'
                ),
                'index' => array(
                    'index'
                ),
            )
        );
    }

    //lay danh sach quyen cua nguoi dung hien tai
    public function getPermisstion()
    {
        return self::$permission;
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function getController()
    {
        return Yii::app()->controller->id;
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {

        $arr = explode('/', $_SERVER['REDIRECT_URL']);
        $system = $arr[1];

        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;

        $list_ignore = $this->controllerIgnore();
        $controller_ignore = $list_ignore[$system];
        if (array_key_exists($controller, $controller_ignore)) {
            $actions = $controller_ignore[$controller];
            if(in_array($action, $actions)) {
                return array();
            }
        }

        //admin system khong thuoc he thong phan quyen
        if ($this->user['admin_system'] == 1) {
            return array();
        }
        if (!empty($this->user['access_url']) && !isset($_GET['test'])) {
            $access_url = trim($this->user['access_url'], '/');
            $request_url = trim($_SERVER['REQUEST_URI'], '/');
            if (strpos($request_url, $access_url) !== 0) {
                die('Access define!');
            }
        }

        //lay danh sach quyen
        $query = "SELECT rule_id FROM {{user_rule}} WHERE uid = " . $this->user['id'];
        $listRule = $this->db->createCommand($query)->queryColumn();
        if (empty($listRule)) {
            die('tài khoản của bạn không được phép truy cập vào hệ thống quản trị');
        }
        //lay danh sach controller co quyen vao
        $query = "SELECT controller, actions FROM {{permission}} WHERE system = '" . addslashes($system) . "' AND rule_id IN (" . implode(',', $listRule) . ")";
        $listController = $this->db->createCommand($query)->queryAll();
        $list = array();
        foreach ($listController as $item) {
            $tmp = unserialize($item['actions']);
            if (isset($list[$item['controller']])) {
                $list[$item['controller']] = array_merge($list[$item['controller']], $tmp);
            } else {
                $list[$item['controller']] = $tmp;
            }
        }

        self::$permission = $list;
        if (isset($list[$controller]) && in_array($action, $list[$controller])) {
            return array(
                array('allow', // allow all users to access 'index' and 'view' actions.
                    'actions' => array(),
                    'users' => array(Yii::app()->user->guestName),
                ),
                array('allow', // allow authenticated users to access all actions
                    'users' => array('@'),
                ),
                array('deny', // deny all users
                    'users' => array('*'),
                ),
            );
        } else {
            echo '<meta charset="utf-8" />';
            die('Bạn không có quyền truy cập chức năng này!');
        }
    }

}
