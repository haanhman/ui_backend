<h3 class="page-title">
    <?php
    $action = Yii::app()->controller->action->id;
    if ($action == 'add') {
        echo 'Thêm mới người dùng';
    } else {
        echo 'Sửa thông tin người dùng: ' . $data['user']['fullname'];
    }
    ?>
</h3>
<div class="row">
    <div class="col-md-12">
        <?php $form = $data['form']; ?>
        <?php echo CHtml::beginForm('', 'POST'); ?>
        <?php echo CHtml::errorSummary($form); ?>
        <?php echo showMessage() ?>
        <div class="col-md-6">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">Thông tin tài khoản</div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form, 'fullname'); ?>
                            <?php echo CHtml::activeTextField($form, 'fullname', array('class' => 'form-control')) ?>
                        </div>
                        <div class="form-group">
                            <?php
                            $attr['class'] = 'form-control';
                            if (Yii::app()->controller->action->id == 'edit') {
                                $attr['readonly'] = true;
                            }
                            ?>
                            <?php echo CHtml::activeLabel($form, 'email'); ?>
                            <?php echo CHtml::activeTextField($form, 'email', $attr) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form, 'password'); ?>
                            <?php echo CHtml::activePasswordField($form, 'password', array('class' => 'form-control')) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form, 're_password'); ?>
                            <?php echo CHtml::activePasswordField($form, 're_password', array('class' => 'form-control')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">Phân quyền</div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label>Chọn nhóm người dùng</label>
                            <br />
                            <?php
                            foreach ($data['rule'] as $item) {
                                echo '<label><input data-checkbox="icheckbox_minimal-red" class="icheck" ' . (in_array($item['id'], $data['listRole']) ? 'checked=""' : '') . ' name="rule[]" type="checkbox" value="' . $item['id'] . '" /> ' . $item['rule'] . '</label><br />';
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form, 'status'); ?>
                            <?php echo CHtml::activeDropDownList($form, 'status', array('1' => 'Hoạt động', '0' => 'Ngừng hoạt động'), array('class' => 'form-control')) ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="form-actions">
            <button type="submit" class="btn blue">Cập nhật</button>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>