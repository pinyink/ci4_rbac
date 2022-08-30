<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<!-- PLUGINS STYLES-->
<link href="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Admin</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">User</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Table</div>
                    <div class="ibox-tools">
                        <a href="javascript:;" onclick="modalAddOpen()" data-toggle="tooltip" data-placement="top" title="Add Data"><i class="fa fa-plus-square"></i></a>
                        <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                        <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Updated At</th>
                                    <th>Aktif</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAdd">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAdd">
                <input type="hidden" name="id" value="">
                <div class="modal-body">
                    <div class="alert alert-info">
                        Password sementara adalah 12345
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label>Username</label>
                            <input class="form-control" type="text" placeholder="username" name="username">
                        </div>
                    </div>
                </div>
                <div class="modal-footer pull-right">
                    <button class="btn btn-primary btn-flat" type="submit">Submit</button>
                    <button type="reset" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?= $this->endSection(); ?>
<?= $this->section('js'); ?>
<script src="<?= base_url(); ?>/assets/alertifyjs/alertify.min.js"></script>
<script src="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<!-- PAGE LEVEL PLUGINS-->
<script src="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var saveMethod;
    var table;
    $(document).ready(function() {
        table = $('#datatable').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('admin/user/ajaxList') ?>",
                "headers": {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                "type": "POST",
                "data": function(data) {
                    data.token = $('meta[name=TOKEN]').attr("content");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            },
            //optional
            "lengthMenu": [
                [5, 10, 25],
                [5, 10, 25]
            ],
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false,
            }, ],
        });
    });

    function reloadTable() {
        table.ajax.reload(null, false);
    }

    function reset_password(id, name) {
        alertify.confirm('Reset Password', 'Reset Password Menjadi 12345 Untuk User ' + name, function() {
            $.ajax({
                    'url': '<?= base_url() ?>/admin/user/resetPassword',
                    'type': 'POST',
                    'dataType': 'JSON',
                    'headers': {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    'data': {
                        'id': id,
                        'token': $('meta[name=TOKEN]').attr("content")
                    },
                })
                .done(function(data) {
                    reloadTable();
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 3, function() {
                        console.log('dismissed');
                    });
                })
                .fail(function(jqXHR) {
                    console.log(jqXHR.responseText);
                });
        }, function() {
            alertify.error('Canceled')
        });
    }

    function aktif_nonaktif(id, aktif, name) {
        var string = '';
        if (aktif == 2) {
            string = 'Non Aktifkan ' + name;
        } else {
            string = 'Aktifkan ' + name;
        }
        alertify.confirm('Alert', string, function() {
            $.ajax({
                    'url': '<?= base_url() ?>/admin/user/nonAktifUser',
                    'type': 'POST',
                    'dataType': 'JSON',
                    'headers': {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    'data': {
                        'id': id,
                        'token': $('meta[name=TOKEN]').attr("content"),
                        'aktif': aktif
                    },
                })
                .done(function(data) {
                    reloadTable();
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 3, function() {
                        console.log('dismissed');
                    });
                })
                .fail(function(jqXHR) {
                    console.log(jqXHR.responseText);
                });
        }, function() {
            alertify.error('Canceled')
        });
    }

    function modalAddOpen() {
        saveMethod = 'save';
        $('#modalAdd .modal-title').text('Add Data');
        $('#modalAdd').modal('show');
        $('#formAdd')[0].reset();
    }

    $(function() {
        $('#formAdd').validate({
            errorClass: "help-block",
            rules: {
                username: {
                    required: true
                },
            },
            highlight: function(e) {
                $(e).closest(".form-group").addClass("has-error")
            },
            unhighlight: function(e) {
                $(e).closest(".form-group").removeClass("has-error")
            },
            submitHandler: function() {
                var dataString = $('#formAdd').serialize() + '&token=' + $('meta[name=TOKEN]').attr("content");
                var url;
                if (saveMethod == 'save') {
                    url = '<?= base_url(); ?>/admin/user/saveData';
                }
                $.ajax({
                        'url': url,
                        'type': 'post',
                        'headers': {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        'dataType': 'JSON',
                        'data': dataString,
                    })
                    .done(function(data) {
                        reloadTable();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                            console.log('dismissed');
                        });
                        $('#modalAdd').modal('hide');
                    })
                    .fail(function(jqXHR) {
                        console.log(jqXHR.responseText);
                    })
                    .always(function() {
                        console.log('reconnect success');
                    });
            }
        });
    });
</script>
<?= $this->endSection(); ?>