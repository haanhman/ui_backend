<div class="row">
    <div class="col-md-12">

        <div style="margin-bottom: 10px;">
            <a href="<?php echo $this->createUrl('add') ?>" class="btn btn-circle red-sunglo btn-sm">
                <i class="fa fa-plus"></i> Thêm mới</a>
        </div>
        <?php echo showMessage(); ?>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Nhóm người dùng</div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Nhóm</th>
                            <th>TT</th>
                            <th>Ngày tạo</th>
                            <th>Sửa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($data['listItem'] as $item) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $item['fullname'] ?></td>
                                <td><?php echo $item['email'] ?></td>
                                <td>
                                    <?php
                                    if (!empty($data['user_rule'][$item['id']])) {
                                        echo implode('<br />', $data['user_rule'][$item['id']]);
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center">
                                    <?php
                                    if ($item['status'] == 1) {
                                        echo '<span style="font-size: 200%; color:blue" class="icon-lock-open"></span>';
                                    } else {
                                        echo '<span style="font-size: 200%;color:red" class="icon-lock"></span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y', $item['created']) ?></td>
                                <td style="text-align: center">
                                    <a class="blue"
                                       href="<?php echo $this->createUrl('edit', array('id' => $item['id'])) ?>">
                                        <i class="icon-pencil"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>