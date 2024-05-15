<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <?= csrf_meta() ?>
    <title><?= isset($judul) ? $judul : ''; ?></title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="<?= base_url('assets/admincast/dist/assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/admincast/dist/assets/vendors/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/admincast/dist/assets/vendors/themify-icons/css/themify-icons.css'); ?>" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <!-- THEME STYLES-->
    <link href="<?= base_url('assets/admincast/dist/assets/css/main_.min.css'); ?>" rel="stylesheet" />
    <link href="<?=base_url('assets/admincast/dist/assets/vendors/sweetalert2/sweetalert2.min.css');?>" rel="stylesheet" type="text/css">
    <?= $this->renderSection('css'); ?>
    <!-- PAGE LEVEL STYLES-->
</head>

<body class="has-animation">
    <div class="page-wrapper">
        <?= $this->include('/tema/header'); ?>
        <?= $this->include('/tema/sidebar'); ?>
        <div class="content-wrapper">
            <?= $this->renderSection('content'); ?>

            <!-- END PAGE CONTENT-->
            <footer class="page-footer">
                <div class="font-13">2018 © <b>AdminCAST</b> - All rights reserved.</div>
                <a class="px-4" href="http://themeforest.net/item/adminca-responsive-bootstrap-4-3-angular-4-admin-dashboard-template/20912589" target="_blank">BUY PREMIUM</a>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer>
        </div>

    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop" id="preloader">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <script src="<?= base_url('assets/admincast/dist/assets/vendors/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admincast/dist/assets/vendors/popper.js/dist/umd/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admincast/dist/assets/vendors/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admincast/dist/assets/vendors/metisMenu/dist/metisMenu.min.js'); ?>"></script>
    <script src="<?= base_url('assets/admincast/dist/assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js'); ?>"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="<?=base_url('assets/admincast/dist/assets/vendors/sweetalert2/sweetalert2.min.js');?>"></script>
    <!-- CORE SCRIPTS-->
    <script src="<?= base_url('assets/admincast/dist/assets/js/app.min.js'); ?>"></script>
    <script>
        $(document)
            .ajaxStart(function() {
                //ajax request went so show the loading image
                $('#preloader').show();
            })
            .ajaxStop(function() {
                //got response so hide the loading image
                $('#preloader').hide();
            });

        /** add active class and stay opened when selected */
        var url_active = window.location;;
        $('ul.side-menu a').filter(function() {
            return this.href == url_active;
        }).parentsUntil($('ul.nav-2-level')).addClass('in');

        $('ul.side-menu a').filter(function() {
            return this.href == url_active;
        }).parents().eq(2).addClass('active');

        $('ul.side-menu a').filter(function() {
            return this.href == url_active;
        }).addClass('active');
    </script>
    <?= $this->renderSection('js'); ?>
    <!-- PAGE LEVEL SCRIPTS-->
</body>

</html>