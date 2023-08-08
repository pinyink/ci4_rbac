
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Admin</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">User</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data User</div>
                    <div class="ibox-tools">
                        <a onclick="reload_table()" class="refresh" data-toggle="tooltip" data-placement="top" title="reload data"><i class="fa fa-refresh"></i></a>
                        <a class="" onclick="tambah_data()" data-toggle="tooltip" data-placement="top" title="tambah data"><i class="fa fa-plus-square"></i></a>
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">Action</th>
                                    <th width="10%">No</th>
									<th style="width: 15.75%">Username</th>
									<th style="width: 10%">Superadmin</th>
                                    <th style="width: 10%">Aktif</th>
                                    <th style="width: 10%">Level / Policy</th>
									<th style="width: 15.75%">Updated At</th>
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

<div class="modal fade" id="modaluser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formuser'], ['user_id' => '', 'method' => '']);?>
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
                        <?=form_label('Username');?>
                        <?=form_input('val_user_username', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Password <small class="text-danger edit">isi jika ingin mereset password</small>');?>
                        <?=form_input('val_user_password', '', ['class' => 'form-control']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('Superadmin');?>
                        <?=form_dropdown('val_user_superadmin', ['' => '-', '1' => 'Ya', '2' => 'Tidak'], '', ['class' => 'form-control']);?>
                    </div>
                    <div class="form-group">
                        <?=form_label('Level / Policy');?>
                        <?=form_dropdown('val_user_level', dropdown($policy, 'policy_id', 'policy_desc'), '', ['class' => 'form-control']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('Aktif');?>
                        <?=form_dropdown('val_user_aktif', ['' => '-', '1' => 'Ya', '2' => 'Tidak'], '', ['class' => 'form-control']);?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
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
                        "url": "<?php echo base_url('/admin/user/ajax_list') ?>",
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

    function reset_form() {
        var MValid = $("#formuser");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        $('.edit').hide();
    }

    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modaluser').modal('show');
        $('#modaluser .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="user_id"]').val(null);
        $('[name="val_user_password"]').val('12345');
    }

    function edit_data(id) {
        reset_form();
        $('.edit').show();
        $('#formuser').valid();
        $('[name="method"]').val('update');
        $.ajax({
            type: "GET",
            url: "<?=base_url('/admin/user/get_data');?>/"+id,
            dataType: "JSON",
            success: function (response) {
                $('#modaluser').modal('show');
                $('#modaluser .modal-title').text('Edit Data');
                $('[name="user_id"]').val(response.user_id);
                $('[name="val_user_username"]').val(response.user_username);
				$('[name="val_user_superadmin"]').val(response.user_superadmin);
				$('[name="val_user_aktif"]').val(response.user_aktif);
            }
        });
    }

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
                url: "<?=base_url('/admin/user/delete_data')?>/"+id,
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

    $(function() {
        $('#formuser').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_user_username: {
                required: true,
				remote: {
                    url: "<?=base_url('/admin/user/user_username_exist'); ?>",
                    type: "post",
                    data: {
                        user_username: function() {
                            return $('[name="val_user_username"]').val();
                        },
                        user_id: function() {
                            return $('[name="user_id"]').val();
                        },
                        csrf_test_name: function() {
                            return $('meta[name=X-CSRF-TOKEN]').attr("content");
                        },
                    },
                },

				maxlength: 32
            },

			val_user_password: {
                
				maxlength: 256
            },

			val_user_superadmin: {
                required: true,
				maxlength: 1
            },

            val_user_level: {
                required: true,
            },

			val_user_aktif: {
                
				maxlength: 1
            },

            },
            messages: {
				val_user_username: {
                    required:'Username harus diisi',remote: 'Username sudah Ada, Tidak bisa di Input',maxlength: 'Username Tidak Boleh Lebih dari 32 Huruf'
                },

				val_user_password: {
                    maxlength: 'Password Tidak Boleh Lebih dari 256 Huruf'
                },

				val_user_superadmin: {
                    required:'Superadmin harus diisi',maxlength: 'Superadmin Tidak Boleh Lebih dari 1 Huruf'
                },

                val_user_level: {
                    required:'Level harus diisi',
                },

				val_user_aktif: {
                    maxlength: 'Aktif Tidak Boleh Lebih dari 1 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = "<?=base_url('/admin/user/save_data');?>";
                var formData = new FormData($($('#formuser'))[0]);
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
                            $('#modaluser').modal('hide');
                        } else {
                            toast_error(response.errorMessage);
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
