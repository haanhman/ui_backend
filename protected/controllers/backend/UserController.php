<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 * @desc Module quản lý người dùng
 * @author anhmantk
 */
class UserController extends BackendController
{

    public function init()
    {
        parent::init();
        $this->_table = 'users';
    }

    //put your code here
    public function actionIndex()
    {
        $data = array();

        //lay danh sach nhom
        $list = $this->getListRule();
        $data['rule'] = array();
        foreach ($list as $item) {
            $data['rule'][$item['id']] = $item['rule'];
        }

        $query = "SELECT * FROM {{" . $this->_table . "}} ORDER BY id DESC";
        $data['listItem'] = $this->db->createCommand($query)->queryAll();

        if (!empty($data['listItem'])) {
            $listId = array();
            foreach ($data['listItem'] as $item) {
                $listId[] = $item['id'];
            }
            $query = "SELECT * FROM {{user_rule}} WHERE uid IN (" . implode(',', $listId) . ")";
            $list = $this->db->createCommand($query)->queryAll();
            foreach ($list as $item) {
                $data['user_rule'][$item['uid']][] = $data['rule'][$item['rule_id']];
            }
        }
        $this->render('index', array('data' => $data));
    }

    public function actionAdd()
    {
        $data = array();
        $form = new UserForm();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $form->attributes = $_POST['UserForm'];
            if ($form->validate()) {
                $values = array();
                foreach ($form->attributes as $key => $vl) {
                    if ($key == 're_password') {
                        continue;
                    }
                    if ($key == 'password') {
                        $vl = md5(trim($vl));
                    }
                    $values[$key] = trim($vl);
                }
                $values['created'] = time();
                yii_insert_row($this->_table, $values);
                $uid = $this->db->lastInsertID;

                $user_rule = formPostParams('rule', VARIABLE_ARRAY);
                if (!empty($user_rule)) {

                    $params = array();
                    foreach ($user_rule as $rule_id) {
                        $params[] = array(
                            'uid' => $uid,
                            'rule_id' => $rule_id
                        );
                    }
                    yii_insert_multiple('user_rule', $params);
                }
                createMessage('Thêm mới người dùng thành công');
                $this->redirect($this->createUrl('index'));
            }
        }

        $data['rule'] = $this->getListRule();

        $data['form'] = $form;
        $this->render('add', array('data' => $data));
    }

    public function actionEdit()
    {
        $uid = urlGETParams('id', VARIABLE_NUMBER);
        $record = $this->getRow($uid);
        if (empty($record)) {
            createMessage('Hệ thống không tìm thấy nội dung bạn yêu cầu', 'danger');
            $this->redirect($this->createUrl('index'));
        }
        $data = array();
        $data['user'] = $record;
        $form = new UserForm();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $form->attributes = $_POST['UserForm'];
            if ($form->validate()) {
                $values = array();
                foreach ($form->attributes as $key => $vl) {
                    if ($key == 're_password') {
                        continue;
                    }
                    if ($key == 'password') {
                        if (empty($vl)) {
                            continue;
                        }
                        $vl = md5(trim($vl));
                    }
                    $values[$key] = trim($vl);
                }
                yii_update_row($this->_table, $values, 'id = ' . $uid);


                //user rule
                //xoa rule hien tai
                $query = "DELETE FROM {{user_rule}} WHERE uid = " . $uid;
                $this->db->createCommand($query)->execute();


                $user_rule = formPostParams('rule', VARIABLE_ARRAY);
                if (!empty($user_rule)) {
                    $params = array();
                    foreach ($user_rule as $rule_id) {
                        $params[] = array(
                            'uid' => $uid,
                            'rule_id' => $rule_id
                        );
                    }
                    yii_insert_multiple('user_rule', $params);
                }
                createMessage('Sửa thông tin người dùng thành công');
                $this->redirect($this->createUrl('index'));
            }
        } else {
            $form->attributes = $record;
            $form->password = '';
        }


        $data['rule'] = $this->getListRule();

        //lay danh sach quyen
        $query = "SELECT rule_id FROM {{user_rule}} WHERE uid = " . $uid;
        $data['listRole'] = $this->db->createCommand($query)->queryColumn();

        $data['form'] = $form;
        $this->render('add', array('data' => $data));
    }

    private function getListRule() {
        $query = "SELECT * FROM {{rule}}";
        return $this->db->createCommand($query)->queryAll();
    }

}
