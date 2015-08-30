<?php

class ChangePasswordForm extends CFormModel {

    public $password_old;
    public $password;
    public $re_password;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('password, re_password', 'required'),
            array('password', 'length', 'min' => 6),
            array('password_old', 'validateOldPassword'),
            array('re_password', 'compare', 'compareAttribute' => 'password')
        );
    }

    public function validateOldPassword() {
        $user = Yii::app()->session['user'];
        if (md5($this->password_old) != $user['password']) {
            $this->addError('password_old', 'Mật khẩu cũ không đúng');
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'password_old' => 'Mật khẩu cũ',
            'password' => 'Mật khẩu mới',
            're_password' => 'Xác nhận mật khẩu mới'
        );
    }

}
