<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<!-- PLUGINS STYLES-->
<link href="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Menu Dua</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Menu Dua</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Menu Dua</div>
                    <div class="ibox-tools">
                        <?php if (enforce(2, 2)) : ?>
                            <a href="javascript:;" class="btn btn-flat btn-sm btn-default mr-1" title="Tambah Data"><i class="fa fa-plus"></i></a>
                        <?php endif ?>
                        <?php if (enforce(2, 3)) : ?>
                            <a href="javascript:;" class="btn btn-flat btn-sm btn-default mr-1" title="Edit Data"><i class="fa fa-edit"></i></a>
                        <?php endif ?>
                        <?php if (enforce(2, 4)) : ?>
                            <a href="javascript:;" class="btn btn-flat btn-sm btn-default mr-1" title="Hapus Data"><i class="fa fa-trash"></i></a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="ibox-body">
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('js'); ?>
<script type="text/javascript">
</script>
<?= $this->endSection(); ?>