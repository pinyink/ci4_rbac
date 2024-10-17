
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
        <li class="breadcrumb-item">Menu</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Menu</div>
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
                                    <th width="15%">Action</th>
                                    <th width="10%">No</th>
									<th style="width: 37.5%">Nama Menu</th>
									<th style="width: 37.5%"> 
                                        <div class="d-flex justify-content-between">
                                            <div>Nama Menu</div>
                                            <div>Aksi</div>
                                        </div>
                                    </th>
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

<div class="modal fade" id="modalmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formmenu'], ['menu_id' => '', 'method' => '']);?>
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
                        <?=form_label('Nama Menu');?>
                        <?=form_input('val_menu_desc', '', ['class' => 'form-control'], 'text');?>
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

<div class="modal fade" id="modalmenuakses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formmenuakses'], ['akses_id' => '', 'method' => '', 'val_menu_id' => '']);?>
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
                        <?=form_label('Kode Akses');?>
                        <?=form_input('val_menu_akses_id', '', ['class' => 'form-control'], 'number');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Nama Akses');?>
                        <?=form_input('val_menu_akses_desc', '', ['class' => 'form-control'], 'text');?>
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
                        "url": "<?php echo base_url('/admin/menu/ajax_list') ?>",
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
                        "targets": [0, 1, 3],
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
        var MValid = $("#formmenu");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        
    }

    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalmenu').modal('show');
        $('#modalmenu .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="menu_id"]').val(null);
    }

    function edit_data(id) {
        reset_form();
        $('#formmenu').valid();
        $('[name="method"]').val('update');
        $.ajax({
            type: "GET",
            url: "<?=base_url('/admin/menu/get_data');?>/"+id,
            dataType: "JSON",
            success: function (response) {
                $('#modalmenu').modal('show');
                $('#modalmenu .modal-title').text('Edit Data');
                $('[name="menu_id"]').val(response.menu_id);
                $('[name="val_menu_desc"]').val(response.menu_desc);
				
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
                url: "<?=base_url('/admin/menu/delete_data')?>/"+id,
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
        $('#formmenu').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_menu_desc: {
                required: true,
				remote: {
                    url: "<?=base_url('/admin/menu/menu_desc_exist'); ?>",
                    type: "post",
                    data: {
                        menu_desc: function() {
                            return $('[name="val_menu_desc"]').val();
                        },
                        menu_id: function() {
                            return $('[name="menu_id"]').val();
                        },
                        <?=csrf_token()?>: function() {
                            return '<?=csrf_hash();?>';
                        },
                    },
                },

				maxlength: 45
            },

            },
            messages: {
				val_menu_desc: {
                    required:'Nama Menu harus diisi',remote: 'Nama Menu sudah Ada, Tidak bisa di Input',maxlength: 'Nama Menu Tidak Boleh Lebih dari 45 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = "<?=base_url('/admin/menu/save_data');?>";
                var formData = new FormData($($('#formmenu'))[0]);
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
                            $('#modalmenu').modal('hide');
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

    function reset_form_akses() {
        var MValid = $("#formmenuakses");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
    }

    function tambah_data_akses(id) {
        save_method = 'save';
        reset_form_akses();
        $('#modalmenuakses').modal('show');
        $('#modalmenuakses .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="akses_id"]').val(null);
        $('[name="val_menu_id"]').val(id);
    }

    function edit_data_akses(id) {
        reset_form_akses();
        $('#formmenuakses').valid();
        $('[name="method"]').val('update');
        $.ajax({
            type: "GET",
            url: "<?=base_url('/admin/menuakses/get_data');?>/"+id,
            dataType: "JSON",
            success: function (response) {
                $('#modalmenuakses').modal('show');
                $('#modalmenuakses .modal-title').text('Edit Data');
                $('[name="akses_id"]').val(response.akses_id);
                $('[name="val_menu_akses_id"]').val(response.menu_akses_id);
				$('[name="val_menu_akses_desc"]').val(response.menu_akses_desc);
				$('[name="val_menu_id"]').val(response.menu_id);
				
            }
        });
    }

    function delete_data_akses(id) {
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
                url: "<?=base_url('/admin/menuakses/delete_data')?>/"+id,
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
        $('#formmenuakses').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_menu_akses_id: {
                required: true,
				maxlength: 11
            },

			val_menu_akses_desc: {
                required: true,
				maxlength: 45
            },

			val_menu_id: {
                required: true,
				maxlength: 11
            },

            },
            messages: {
				val_menu_akses_id: {
                    required:'Id harus diisi',maxlength: 'Id Tidak Boleh Lebih dari 11 Huruf'
                },

				val_menu_akses_desc: {
                    required:'Nama Akses harus diisi',maxlength: 'Nama Akses Tidak Boleh Lebih dari 45 Huruf'
                },

				val_menu_id: {
                    required:'Nama Menu harus diisi',maxlength: 'Nama Menu Tidak Boleh Lebih dari 11 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = "<?=base_url('/admin/menuakses/save_data');?>";
                var formData = new FormData($($('#formmenuakses'))[0]);
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
                            $('#modalmenuakses').modal('hide');
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
