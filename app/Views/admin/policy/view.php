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
        <h1 class="page-title">Admin</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Policy</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox small">
                    <div class="ibox-head">
                        <div class="ibox-title">Data Table</div>
                        <div class="ibox-tools">
                            <a href="javascript:;" onclick="modalAddOpen()" data-toggle="tooltip" data-placement="top" title="Add Data"><i class="fa fa-plus-square"></i></a>
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Desc</th>
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
<!-- hide the content -->
<div id="page-content-more" class="hide"></div>

<!-- modal -->
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
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label>Desc Policy</label>
                            <input class="form-control" type="text" placeholder="Desc Policy" name="descPolicy">
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

<div class="modal fade" id="modalUser">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="datatableUser" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">Aksi</th>
                            <th width="15%">No</th>
                            <th width="70%">User</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalMenu">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="datatableMenu" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="90%">Menu</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
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
    var tableUser;
    var policy_Id;
    var tableMenu;

    $(document).ready(function() {
        table = $('#datatable').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('admin/policy/ajaxList') ?>",
                "headers": {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                "type": "POST",
                "data": {<?=csrf_token();?>: '<?=csrf_hash()?>',},
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            },
            //optional
            "lengthMenu": [
                [10, 25],
                [10, 25]
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

    function modalAddOpen() {
        saveMethod = 'save';
        $('#formAdd')[0].reset();
        $('#modalAdd').modal('show');
        $('#modalAdd .modal-title').html('<i class="fa fa-plus"></i> Add Policy');
    }

    function editData(id) {
        saveMethod = 'update';
        $('#formAdd')[0].reset();
        $('#modalAdd').modal('show');
        $('#modalAdd .modal-title').html('<i class="fa fa-edit"></i> Edit Policy');
        $.ajax({
                'url': '<?= base_url() ?>/admin/policy/getData/' + id,
                'type': 'PUT',
                'dataType': 'json'
            })
            .done(function(data) {
                $('[name="id"]').val(data.policy_id);
                $('[name="descPolicy"]').val(data.policy_desc);
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            })
            .always();
    }

    $(function() {
        $('#formAdd').validate({
            errorClass: "help-block",
            rules: {
                descPolicy: {
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
                    url = '<?= base_url(); ?>/admin/policy/saveData';
                } else if (saveMethod == 'update') {
                    url = '<?= base_url(); ?>/admin/policy/updateData';
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

    function user(id) {
        policy_Id = id;
        $('#modalUser').modal('show');
        tableUser = $('#datatableUser').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url('admin/policy/userList') ?>",
                "headers": {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                "type": "POST",
                "data": function(data) {
                    data.token = $('meta[name=TOKEN]').attr("content");
                    data.policy = id;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            },
            //optional
            "lengthMenu": [
                [10, 25],
                [10, 25]
            ],
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false,
            }, ],
            "bDestroy": true
        });
    }

    function add_role(id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/addRole',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    user_id: id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
                tableUser.ajax.reload(null, false);
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }

    function remove_role(id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/removeRole',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    user_id: id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
                tableUser.ajax.reload(null, false);
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }
</script>
<?= $this->endSection(); ?>