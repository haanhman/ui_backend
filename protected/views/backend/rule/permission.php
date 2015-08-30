<h3 class="page-title">Phân quyền cho nhóm: <span class=""><?php echo $data['rule']['rule'] ?></span></h3>
<div class="row">
    <div class="col-md-12">
        <div class="tabbable-line boxless tabbable-reversed">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?php echo $this->createUrl('permission', $option) ?>" data-toggle="tab"
                       aria-expanded="false">Hệ thống quản trị</a>
                </li>
            </ul>
        </div>

        <?php echo showMessage(); ?>
        <?php echo CHtml::beginForm('', 'POST', array('class' => 'form-horizontal')); ?>
        <?php
        $listController = $data['listController'];
        foreach ($data['listItem'] as $item) {
            ?>
            <div class="form-group">
                <label class="col-sm-12 control-label"
                       style="text-align: left; font-weight: bold;"><?php echo ucfirst($item['controller']) ?></label>

                <div class="col-sm-12">
                    <?php
                    if (!empty($item['description'])) {
                        echo '<p class="desc">' . $item['description'] . '</p>';
                    }
                    if (!empty($item['actions'])) {
                        $list = isset($listController[$item['controller']]) ? $listController[$item['controller']] : array();
                        echo '<ul class="rule">';
                        foreach ($item['actions'] as $ac) {
                            echo '<li><label><input  data-checkbox="icheckbox_minimal-red" class="icheck"  ' . (in_array($ac, $list) ? 'checked=""' : '') . ' name="' . $item['controller'] . '[]" type="checkbox" value="' . $ac . '" /> ' . $ac . '</label></li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-actions left">
            <button type="button" class="btn default" onclick="window.location='<?php echo $this->createUrl('index') ?>';">Huỷ</button>
            <button type="submit" class="btn blue"><i class="fa fa-check"></i> Cập nhật</button>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>

<style type="text/css">
    ul.rule {
        padding: 0px;
        margin: 0px
    }

    ul.rule li {
        list-style: none;
        display: inline-block;
        padding-right: 10px;
    }

    div.form-group {
        border-bottom: 1px dotted #E2E2E2
    }

    p.desc {
        font-size: 11px;
        color: gray
    }
</style>
