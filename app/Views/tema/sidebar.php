<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex align-items-center justify-content-center flex-column">
            <div>
                <img src="<?= session('profil_image'); ?>" class="imgProfil img-circle admin-avatar" />
            </div>
            <div class="admin-info">
                <div class="font-strong"><?= session('fullname') ?></div>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a href="<?= base_url('home'); ?>"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-podcast"></i>
                    <span class="nav-label">Blog</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse">
                    <?php if (enforce(1, 1)) : ?>
                        <li>
                            <a href="<?= base_url('blog/categories'); ?>">Categories</a>
                        </li>
                    <?php endif ?>
                    <?php if (enforce(2, 1)) : ?>
                        <li>
                            <a href="<?= base_url('blog/filemanager'); ?>">File Manager</a>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
            <?php if (session('level') == 1) : ?>
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-gear"></i>
                        <span class="nav-label">Admin Menu</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="<?= base_url('admin/user'); ?>">User</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/policy'); ?>">Policy</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/menu'); ?>">Menu</a>
                        </li>
                        <li>
                            <a href="<?= base_url('crud'); ?>">Crud</a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->