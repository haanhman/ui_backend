<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 * @desc Phân quyền
 * @author anhmantk
 */
class RuleController extends BackendController
{

    public function init()
    {
        $this->_table = 'rule';
        parent::init();
    }

    //put your code here
    public function actionIndex()
    {
        $data = array();
        $query = "SELECT * FROM {{" . $this->_table . "}} ORDER BY id DESC";
        $data['listItem'] = $this->db->createCommand($query)->queryAll();
        $this->render('index', array('data' => $data));
    }

    public function actionAdd()
    {
        $data = array();
        $form = new RuleForm('add');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $form->attributes = $_POST['RuleForm'];
            if ($form->validate()) {
                $values = array('rule' => trim($form->rule));
                yii_insert_row($this->_table, $values);
                createMessage('Thêm mới nhóm người dùng thành công');
                $this->redirect($this->createUrl('index'));
            }
        }
        $data['form'] = $form;
        $this->render('add', array('data' => $data));
    }

    public function actionEdit()
    {
        $data = array();
        $rule_id = urlGETParams('id', VARIABLE_NUMBER);
        $record = $this->getRow($rule_id);
        if (empty($record)) {
            createMessage('Nội dung bạn yêu cầu không tồn tại');
            $this->redirect($this->createUrl('index'));
        }


        $form = new RuleForm('edit');
        $form->id = $record['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $form->attributes = $_POST['RuleForm'];
            if ($form->validate()) {
                $values = array('rule' => trim($form->rule));
                yii_update_row($this->_table, $values, 'id = ' . $rule_id);
                createMessage('Cập nhật nhóm người dùng thành công');
                $this->redirect($this->createUrl('index'));
            }
        } else {
            $form->attributes = $record;
            $form->id = $record['id'];
        }
        $data['form'] = $form;
        $this->render('add', array('data' => $data));
    }

    public function actionPermission()
    {
        $data = array();
        $rule_id = urlGETParams('id', VARIABLE_NUMBER);
        $record = $this->getRow($rule_id);
        if (empty($record)) {
            createMessage('Nội dung bạn yêu cầu không tồn tại');
            $this->redirect($this->createUrl('index'));
        }


        $data['rule'] = $record;
        $system = isset($_GET['system']) ? trim($_GET['system']) : 'backend';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST)) {
                $query_delete = "DELETE FROM {{permission}} WHERE rule_id = :rule_id AND system = :system";
                $values = array(
                    ':rule_id' => $rule_id,
                    ':system' => $system
                );
                $this->db->createCommand($query_delete)->bindValues($values)->execute();


                $params = array();
                foreach ($_POST as $controller => $actions) {
                    $params[] = array(
                        'system' => $system,
                        'rule_id' => $rule_id,
                        'controller' => $controller,
                        'actions' => serialize($actions)
                    );
                }
                if (!empty($params)) {
                    yii_insert_multiple('permission', $params);
                }
            }
            createMessage('Cập nhật thành công');
            $this->redirect($_SERVER['HTTP_REFERER']);
        }


        $query = "SELECT * FROM {{permission}} WHERE system = :system AND rule_id = :rule_id";
        $values = array(
            ':system' => 'backend',
            ':rule_id' => $rule_id,
        );

        $listController = $this->db->createCommand($query)->bindValues($values)->queryAll();

        $data['listController'] = array();
        if (!empty($listController)) {
            foreach ($listController as $item) {
                $data['listController'][$item['controller']] = unserialize($item['actions']);
            }
        }
        $myPath = ROOT_PATH . '/protected/controllers/' . $system;

        $controller_ignore = $this->controllerIgnore();


        $dir = new DirectoryIterator($myPath);
        $listClass = array();
        foreach ($dir as $fileinfo) {
            $pattern = '/.*\.(php)/i';
            if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                if (preg_match($pattern, $fileinfo->getFilename())) {
                    $filename = $fileinfo->getFilename();
                    //var_dump(file_exists($path . '/' . $filename));
                    //echo $myPath . '/' . $filename . '<br />';
                    include_once $myPath . '/' . $filename;
                    $filename = substr($filename, 0, strlen($filename) - 4);
                    $filename_tmp = str_replace('Controller', '', $filename);
                    if (in_array(strtolower($filename_tmp), $controller_ignore[$system])) {
                        continue;
                    }
                    $listClass[] = $filename;
                }
            }
        }

        $listAction = array();
        foreach ($listClass as $class) {
            $controller = strtolower(str_replace('Controller', '', $class));

            $listAction[$controller]['name'] = $class;
            $refClass = new ReflectionClass($class);
            $doc = $refClass->getDocComment();
            preg_match_all('#@desc(.*?)\n#s', $doc, $desc);
            $listAction[$controller]['description'] = trim($desc[1][0]);
            $listAction[$controller]['controller'] = $controller;


            $actions = array();
            $class_methods = get_class_methods(new $class);
            foreach ($class_methods as $method) {
                $reflect = new ReflectionMethod($class, $method);
                if (strpos($method, 'action') === 0 && $reflect->isPublic() && $method != 'actions') {
                    $actions[] = strtolower(substr($method, 6, strlen($method)));
                }
            }
            $listAction[$controller]['actions'] = $actions;
        }

        $data['listItem'] = $listAction;

        $this->render('permission', array('data' => $data));
    }

}
