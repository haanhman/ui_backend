<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author anhmantk
 */
class LoginController extends CController
{

    protected $user;
    private $_table;
    /**
     *
     * @var CDbConnection
     */
    private $db;

    public function init()
    {
        parent::init();
        $this->user = Yii::app()->session['user'];
        $this->_table = 'users';
        $this->db = EduDataBase::getConnection();
        $this->layout = null;
    }

    //put your code here
    public function actionIndex()
    {
        $data = array();
        if (!empty($_GET['dest'])) {
            $redirect = base64_decode($_GET['dest']);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_GET['dest'])) {
                $redirect = $this->createUrl('index/index');
            }

            $email = formPostParams('email', VARIABLE_STRING);
            $password = formPostParams('password', VARIABLE_STRING);
            $condition = array(
                ':email' => $email,
                ':password' => md5($password)
            );
            $query = "SELECT * FROM {{" . $this->_table . "}} WHERE email = :email AND password = :password";
            $user = $this->db->createCommand($query)->bindValues($condition)->queryRow();

            if (!empty($user)) {
                Yii::app()->session['user'] = $user;
                $this->redirect($redirect);
            } else {
                createMessage('Email hoặc mật khẩu không đúng', 'danger');
            }
        } else {
            $user = Yii::app()->session['user'];
            if (!empty($user)) {
                $this->redirect($redirect);
            }
        }
        $this->renderPartial('index', array('data' => $data));
    }

    public function actionLogout()
    {
        $user = Yii::app()->session['user'];
        if (!empty($user)) {
            unset(Yii::app()->session['user']);
            Yii::app()->session->clear();
        }
        $this->redirect($this->createUrl('login/index'));
    }

}
