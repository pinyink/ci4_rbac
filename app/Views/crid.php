
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Crid</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        
        <li class="breadcrumb-item">Crid</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Crid</div>
                    <div class="ibox-tools">
                        <a onclick="reload_table()" class="refresh" data-toggle="tooltip" data-placement="top" title="reload data"><i class="fa fa-refresh"></i></a>
                        <?php if(enforce(1, 2)): ?>
                            <a class="" onclick="tambah_data()" data-toggle="tooltip" data-placement="top" title="tambah data"><i class="fa fa-plus-square"></i></a>
                        <?php endif ?>
                        <?php if(enforce(1, 5)): ?>
                            
                        <?php endif ?>
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">Action</th>
                                    <th width="5%">No</th>
									<th style="width: 10.714285714286%">Table</th>
                                    <th style="width: 10%;">Route</th>
									<th style="width: 10.714285714286%">Namespace</th>
									<th style="width: 10.714285714286%">Title</th>
									<th style="width: 10.714285714286%">Primary Key</th>
									<th style="width: 10.714285714286%">V Created At</th>
									<th style="width: 10.714285714286%">V Updated At</th>
									<th style="width: 10.714285714286%">V Deleted At</th>
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
    <!-- /Row -->
</div>
<!-- /Container -->

<div class="modal fade" id="modalcrid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <?=form_open('', ['id' => 'formcrid'], ['id' => '', 'method' => '']);?>
            <?=csrf_field();?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
					<div class="form-group">
                        <?=form_label('Table');?>
                        <?=form_input('val_table', '', ['class' => 'form-control'], 'text');?>
                    </div>
                    <div class="form-group">
                        <?=form_label('Route Name');?>
                        <?=form_input('val_routename', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Namespace');?>
                        <?=form_input('val_namespace', '\\', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Title');?>
                        <?=form_input('val_title', '', ['class' => 'form-control'], 'text');?>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <?=form_label('Primary Key');?>
                                <?=form_input('val_primary_key', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <?=form_label('RBAC');?>
                                <?=form_input('val_rbac', '', ['class' => 'form-control'], 'number');?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <?=form_label('V Created At');?>
                                <?=form_input('val_v_created_at', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <?=form_label('V Updated At');?>
                                <?=form_input('val_v_updated_at', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <?=form_label('V Deleted At');?>
                                <?=form_input('val_v_deleted_at', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        <?=form_close();?>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('js');?>
<script src="<?=base_url(); ?>/assets/alertifyjs/alertify.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/moment/min/moment.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"> </script>

<script>
    var table;
    var save_method;
    $(document).ready(function () {
        table = $('#datatable').DataTable({
                    scrollCollapse: true,
                    responsive: true,
                    autoWidth: false,
                    language: { search: "",
                        searchPlaceholder: "Search",
                        sLengthMenu: "_MENU_items"
                    },
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('/crid/ajax_list') ?>",
                        "headers": {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        "type": "POST",
                        "data": {<?=csrf_token();?>: '<?=csrf_hash()?>'},
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    },
                    //optional
                    "columnDefs": [{
                        "targets": [0, 1],
                        "orderable": false,
                    }, ],
                });
    });
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    $(document).ready(function () {
	});
    

    function reset_form() {
        var MValid = $("#formcrid");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        
    }

    <?php if(enforce(1, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalcrid').modal('show');
        $('#modalcrid .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="id"]').val(null);
        $('#formcrid .form-control').removeClass('form-view-detail');
        $('#formcrid .form-control').prop('disabled', false);
        $('#formcrid button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(1, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formcrid').valid();
        $('[name="method"]').val('update');
        $('#formcrid .form-control').removeClass('form-view-detail');
        $('#formcrid .form-control').prop('disabled', false);
        $('#formcrid button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/crid');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalcrid').modal('show');
                $('#modalcrid .modal-title').text('Edit Data');
                $('[name="id"]').val(response.id);
                $('[name="val_table"]').val(response.table);
				$('[name="val_namespace"]').val(response.namespace);
				$('[name="val_title"]').val(response.title);
				$('[name="val_primary_key"]').val(response.primary_key);
				$('[name="val_v_created_at"]').val(response.v_created_at);
				$('[name="val_v_updated_at"]').val(response.v_updated_at);
				$('[name="val_v_deleted_at"]').val(response.v_deleted_at);
				$('[name="val_routename"]').val(response.routename);
				$('[name="val_rbac"]').val(response.rbac);
				
            }
        });
    }
    <?php endif ?>

    <?php if(enforce(1, 4)): ?>
    function delete_data(id) {
        Swal.fire({
        title: 'Apa Anda Yakin?',
        text: "Anda Tidak Dapat Mengembalikan Data Yang Telah Di Hapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Hapus !'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: "<?=base_url('/crid')?>/"+id+'/delete_data',
                dataType: "json",
                success: function (response) {
                    if(response.errorCode == 1) {
                        Swal.fire(
                            'Deleted!',
                            'Data Berhasil Di Hapus.',
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Deleted Failed!',
                            'Data Gagal Di Hapus',
                            'warning'
                        )
                    }
                    reload_table();
                }
            });
        }
        })

    }
    <?php endif ?>

    $(function() {
        $('#formcrid').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_table: {
                required: true,
				remote: {
                    url: "<?=base_url('/crid/table_exist'); ?>",
                    type: "post",
                    data: {
                        table: function() {
                            return $('[name="val_table"]').val();
                        },
                        id: function() {
                            return $('[name="id"]').val();
                        },
                        csrf_test_name: function() {
                            return $('meta[name=X-CSRF-TOKEN]').attr("content");
                        },
                    },
                },

				maxlength: 64
            },

			val_title: {
                required: true,
				maxlength: 64
            },

			val_primary_key: {
                required: true,
				maxlength: 64
            },

			val_v_created_at: {
                required: true,
				maxlength: 64
            },

			val_v_updated_at: {
                required: true,
				maxlength: 64
            },

			val_v_deleted_at: {
                required: true,
				maxlength: 64
            },

            },
            messages: {
				val_table: {
                    required:'Table harus diisi',remote: 'Table sudah Ada, Tidak bisa di Input',maxlength: 'Table Tidak Boleh Lebih dari 64 Huruf'
                },

				val_namespace: {
                    required:'Namespace harus diisi',maxlength: 'Namespace Tidak Boleh Lebih dari 64 Huruf'
                },

				val_title: {
                    required:'Title harus diisi',maxlength: 'Title Tidak Boleh Lebih dari 64 Huruf'
                },

				val_primary_key: {
                    required:'Primary Key harus diisi',maxlength: 'Primary Key Tidak Boleh Lebih dari 64 Huruf'
                },

				val_v_created_at: {
                    required:'V Created At harus diisi',maxlength: 'V Created At Tidak Boleh Lebih dari 64 Huruf'
                },

				val_v_updated_at: {
                    required:'V Updated At harus diisi',maxlength: 'V Updated At Tidak Boleh Lebih dari 64 Huruf'
                },

				val_v_deleted_at: {
                    required:'V Deleted At harus diisi',maxlength: 'V Deleted At Tidak Boleh Lebih dari 64 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = '';
                if(save_method == 'update') {
                    url = "<?=base_url('/crid/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/crid/save_data');?>";
                }
                var formData = new FormData($($('#formcrid'))[0]);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType:false,
                    cache : false,
                    success: function (response) {
                        if(response.errorCode == 1) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                            reload_table();
                            $('#modalcrid').modal('hide');
                        } else {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                        }
                    },
                    error: function(jqXHR){
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    });
</script>
<?=$this->endSection();?>
