<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-off green home-icon"></i>
            <a href="<?php echo $this->createUrl('login/logout') ?>">Đăng xuất</a>
        </li>
    </ul>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <h1>Thay đổi mật khẩu</h1>
            </div>
            <?php $form = $data['form']; ?>
            <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal')); ?>
            <?php echo CHtml::errorSummary($form); ?>
            <?php echo showMessage(); ?>
            <div class="form-group">                
                <?php echo CHtml::activeLabel($form, 'password_old', array('class' => 'col-sm-3 control-label')); ?>
                <div class="col-sm-9">
                    <?php echo CHtml::activePasswordField($form, 'password_old') ?>
                </div>
            </div>
            <div class="form-group">                
                <?php echo CHtml::activeLabel($form, 'password', array('class' => 'col-sm-3 control-label')); ?>
                <div class="col-sm-9">
                    <?php echo CHtml::activePasswordField($form, 'password') ?>
                </div>
            </div>
            <div class="form-group">                
                <?php echo CHtml::activeLabel($form, 're_password', array('class' => 'col-sm-3 control-label')); ?>
                <div class="col-sm-9">
                    <?php echo CHtml::activePasswordField($form, 're_password') ?>
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="submit">
                        <i class="icon-ok bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="icon-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
</div>
