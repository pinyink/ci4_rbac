
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
    <h1 class="page-title">Siswa</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        
        <li class="breadcrumb-item">Siswa</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data siswa</div>
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
                                    <th width="15%">Action</th>
                                    <th width="10%">No</th>
									<th style="width: 18.75%">Nama</th>
									<th style="width: 18.75%">Alamat</th>
									<th style="width: 18.75%">Tempat Lahir</th>
									<th style="width: 18.75%">Tanggal Lahir</th>
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

<div class="modal fade" id="modalsiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formsiswa'], ['siswa_id' => '', 'method' => '']);?>
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
                        <?=form_label('Nama');?>
                        <?=form_input('val_siswa_nama', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Alamat');?>
                        <?=form_input('val_siswa_alamat', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Tempat Lahir');?>
                        <?=form_input('val_siswa_tempat_lahir', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Tanggal Lahir');?>
                        <?=form_input('val_siswa_tanggal_lahir', '', ['class' => 'form-control']);?>
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
                        "url": "<?php echo base_url('/siswa/ajax_list') ?>",
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
    

	$('[name="val_siswa_tanggal_lahir"]').datepicker({
		todayBtn: "linked",
		keyboardNavigation: false,
		forceParse: false,
		calendarWeeks: true,
		autoclose: true,
		format: "dd-mm-yyyy"
	});

    function reset_form() {
        var MValid = $("#formsiswa");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        
    }

    function lihat_data(id) {
        reset_form();
        save_method = 'update';
        $('#formsiswa').valid();
        $('[name="method"]').val('update');
        $('#formsiswa .form-control').addClass('form-view-detail');
        $('#formsiswa .form-control').prop('disabled', true);
        $('#formsiswa button[type="submit"]').hide();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/siswa');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalsiswa').modal('show');
                $('#modalsiswa .modal-title').text('Detail Data');
                $('[name="siswa_id"]').val(response.siswa_id);
                $('[name="val_siswa_nama"]').val(response.siswa_nama);
				$('[name="val_siswa_alamat"]').val(response.siswa_alamat);
				$('[name="val_siswa_tempat_lahir"]').val(response.siswa_tempat_lahir);
				$('[name="val_siswa_tanggal_lahir"]').val(response.siswa_tanggal_lahir);
				
            }
        });
    }

    <?php if(enforce(1, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalsiswa').modal('show');
        $('#modalsiswa .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="siswa_id"]').val(null);
        $('#formsiswa .form-control').removeClass('form-view-detail');
        $('#formsiswa .form-control').prop('disabled', false);
        $('#formsiswa button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(1, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formsiswa').valid();
        $('[name="method"]').val('update');
        $('#formsiswa .form-control').removeClass('form-view-detail');
        $('#formsiswa .form-control').prop('disabled', false);
        $('#formsiswa button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/siswa');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalsiswa').modal('show');
                $('#modalsiswa .modal-title').text('Edit Data');
                $('[name="siswa_id"]').val(response.siswa_id);
                $('[name="val_siswa_nama"]').val(response.siswa_nama);
				$('[name="val_siswa_alamat"]').val(response.siswa_alamat);
				$('[name="val_siswa_tempat_lahir"]').val(response.siswa_tempat_lahir);
				$('[name="val_siswa_tanggal_lahir"]').val(response.siswa_tanggal_lahir);
				
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
                url: "<?=base_url('/siswa')?>/"+id+'/delete_data',
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
        $('#formsiswa').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_siswa_nama: {
                required: true,
				remote: {
                    url: "<?=base_url('/siswa/siswa_nama_exist'); ?>",
                    type: "post",
                    data: {
                        siswa_nama: function() {
                            return $('[name="val_siswa_nama"]').val();
                        },
                        siswa_id: function() {
                            return $('[name="siswa_id"]').val();
                        },
                        csrf_test_name: function() {
                            return $('meta[name=X-CSRF-TOKEN]').attr("content");
                        },
                    },
                },

				maxlength: 128
            },

			val_siswa_alamat: {
                
            },

			val_siswa_tempat_lahir: {
                
				maxlength: 32
            },

			val_siswa_tanggal_lahir: {
                
            },

            },
            messages: {
				val_siswa_nama: {
                    required:'Nama harus diisi',remote: 'Nama sudah Ada, Tidak bisa di Input',maxlength: 'Nama Tidak Boleh Lebih dari 128 Huruf'
                },

				val_siswa_alamat: {
                    
                },

				val_siswa_tempat_lahir: {
                    maxlength: 'Tempat Lahir Tidak Boleh Lebih dari 32 Huruf'
                },

				val_siswa_tanggal_lahir: {
                    
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
                    url = "<?=base_url('/siswa/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/siswa/save_data');?>";
                }
                var formData = new FormData($($('#formsiswa'))[0]);
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
                            $('#modalsiswa').modal('hide');
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
