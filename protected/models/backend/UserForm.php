<?php

class UserForm extends CFormModel {

    public $fullname;
    public $email;
    public $password;
    public $re_password;
    public $status;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        if (Yii::app()->controller->action->id == 'add') {
            return array(
                array('fullname, email, password, re_password', 'required'),
                array('re_password', 'compare', 'compareAttribute' => 'password'),
                array('email', 'email'),
                array('email', 'emailUnique', 'on' => 'edit'),
                array('status, email, password, re_password', 'default')
            );
        } else {
            return array(
                array('fullname', 'required'),
                array('re_password', 'compare', 'compareAttribute' => 'password'),
                array('status, email, password, re_password', 'default')
            );
        }
    }

    public function emailUnique()
    {
        $db = EduDataBase::getConnection();
        $query = "SELECT * FROM {{users}} WHERE email = :email";
        $values = array(':email' => $this->email);
        $recored = $db->createCommand($query)->bindValues($values)->queryRow();
        if (!empty($recored)) {
            $this->addError('email', 'Email "' . $this->email . '" đã tồn tại rồi');
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'fullname' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            're_password' => 'Xác nhận mật khẩu',
            'status' => 'Trạng thái'
        );
    }

}
