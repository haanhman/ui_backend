<div class="row">
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">Thay đổi mật khẩu</div>
            </div>
            <div class="portlet-body form">
                <?php $form = $data['form']; ?>
                <?php echo CHtml::beginForm('', 'POST'); ?>
                <?php echo CHtml::errorSummary($form); ?>
                <?php echo showMessage() ?>
                <div class="form-body">
                    <div class="form-group">
                        <?php echo CHtml::activeLabel($form, 'password_old'); ?>
                        <?php echo CHtml::activePasswordField($form, 'password_old', array('class' => 'form-control')) ?>
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
                <div class="form-actions">
                    <button type="submit" class="btn blue">Thay đổi</button>
                </div>
                <?php echo CHtml::endForm(); ?>
            </div>
        </div>
    </div>
</div>