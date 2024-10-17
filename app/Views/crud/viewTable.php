<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<!-- PLUGINS STYLES-->
<link href="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div id="page-content-menu">
    <div class="page-heading">
        <h1 class="page-title">Crud Generator</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Crud</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox small">
                    <div class="ibox-body">
                        <?=form_open(base_url('crud/table'), ['id' => 'form_crud'])?>
                        <?=csrf_field();?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Table</label>
                                    <select name="table" id="table" class="form-control">
                                        <option value="">-</option>
                                        <?php foreach($tables as $value):?>
                                            <option value="<?=$value;?>"><?=$value?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                        <?=form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- hide the content -->
<div id="page-content-more" class="hide"></div>

<?= $this->endSection(); ?>
<?= $this->section('js'); ?>
<script type="text/javascript">
<?= $this->endSection(); ?>