<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        <li class="dropdown dropdown-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
               data-close-others="true">
                <img alt="" class="img-circle"
                     src="<?php echo base_url() ?>/public/assets/admin/layout/img/avatar3_small.jpg"/>
					<span class="username username-hide-on-mobile"><?php echo $this->user['fullname'] ?></span>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li>
                    <a href="<?php echo $this->createUrl('profile/password') ?>">
                        <i class="icon-key"></i> Thay đổi mật khẩu </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('login/logout') ?>">
                        <i class="icon-login"></i> Đăng xuất </a>
                </li>
            </ul>
        </li>
        <li class="dropdown dropdown-quick-sidebar-toggler">
            <a href="<?php echo $this->createUrl('login/logout') ?>" class="dropdown-toggle">
                <i class="icon-login"></i>
            </a>
        </li>
    </ul>
</div>