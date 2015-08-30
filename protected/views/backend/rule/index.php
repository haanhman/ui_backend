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
                            <th>Tên nhóm</th>
                            <th>Phân quyền</th>
                            <th>Sửa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($data['listItem'] as $item) {
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $item['rule'] ?></td>
                                <td>
                                    <a href="<?php echo $this->createUrl('permission', array('id' => $item['id'])) ?>">Phân quyền</a>
                                </td>
                                <td>
                                    <a href="<?php echo $this->createUrl('edit', array('id' => $item['id'])) ?>"><i class="icon-pencil"></i></a>
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