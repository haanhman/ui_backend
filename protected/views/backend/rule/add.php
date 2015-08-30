<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i> Thêm mới nhóm người dùng
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = $data['form']; ?>
                <?php echo CHtml::beginForm('', 'POST'); ?>
                <?php echo CHtml::errorSummary($form); ?>
                <div class="form-body">
                    <div class="form-group">
                        <?php echo CHtml::activeLabel($form, 'rule'); ?>

                        <div class="input-group">
											<span class="input-group-addon">
											<i class="fa fa-users"></i>
											</span>
                            <?php echo CHtml::activeTextField($form, 'rule', array('class' => 'form-control', 'placeholder' => 'Nhập tên nhóm')) ?>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <button type="submit" class="btn blue">Submit</button>
                </div>
                <?php echo CHtml::endForm(); ?>
            </div>
        </div>
    </div>
</div>