<?php

class ProfileController extends BackendController
{
    public function actionPassword() {
        $data = array();
        $form = new ChangePasswordForm();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $form->attributes = $_POST['ChangePasswordForm'];
            if ($form->validate()) {
                $uid = Yii::app()->session['user']['id'];
                $query = "UPDATE {{users}} SET password = :password WHERE id = " . $uid;
                $this->db->createCommand($query)->bindValues(array(':password' => md5($form->password)))->execute();

                Yii::app()->session['user']['password'] = md5($form->password);
                createMessage('Thay đổi mật khẩu thành công');
                $this->redirect($this->createUrl('password'));
            }
        }
        $data['form'] = $form;
        $this->render('password', array('data' => $data));
    }
}
