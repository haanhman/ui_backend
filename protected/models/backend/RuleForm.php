<?php

class RuleForm extends CFormModel
{

    public $id;
    public $rule;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('rule', 'required'),
            array('rule', 'ruleUnique', 'on' => 'add'),
            array('rule', 'ruleUnique', 'on' => 'edit'),
        );
    }

    public function ruleUnique()
    {
        $action = Yii::app()->controller->action->id;
        $db = EduDataBase::getConnection();
        $query = "SELECT * FROM {{rule}} WHERE rule = :rule";
        if ($action == 'edit') {
            $query .= " AND id <> " . $_GET['id'];
        }
        $values = array(':rule' => $this->rule);
        $recored = $db->createCommand($query)->bindValues($values)->queryRow();
        if (!empty($recored)) {
            $this->addError('rule', 'Nhóm "' . $this->rule . '" đã tồn tại rồi');
        }
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public
    function attributeLabels()
    {
        return array(
            'rule' => 'Tên nhóm'
        );
    }

}
