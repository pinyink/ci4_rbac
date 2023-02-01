
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
        <li class="breadcrumb-item">Master</li>
        <li class="breadcrumb-item">Desa</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Desa</div>
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
									<th style="width: 15%">Ds</th>
									<th style="width: 15%">Kec</th>
									<th style="width: 15%">Kab</th>
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

<div class="modal fade" id="modaldesa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formdesa'], ['id_desa' => '', 'method' => '']);?>
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
                        <?=form_label('The Geom ( shp )');?>
                        <?=form_upload('val_the_geom', '', ['class' => 'form-control', 'accept' => '.shp', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('The Geom ( shx )');?>
                        <?=form_upload('val_the_geom_shx', '', ['class' => 'form-control', 'accept' => '.shx', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('The Geom ( dbf )');?>
                        <?=form_upload('val_the_geom_dbf', '', ['class' => 'form-control', 'accept' => '.dbf', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?>
                    </div>
					<div class="form-group">
                        <?=form_label('Ds');?>
                        <?=form_input('val_ds', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Kec');?>
                        <?=form_input('val_kec', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Kab');?>
                        <?=form_input('val_kab', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Image', 'val_image');?>
                        <?=form_upload('val_image', '', ['class' => 'form-control', 'id' => 'val_image', 'accept' => ".png,.jpg,.jpeg", 'onchange' => "readURL(this, 'img-preview-image');"]);?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?=base_url('assets/admincast/dist/assets/img/image.jpg') ?>" alt="" class="img img-thumbnail img-preview " id="img-preview-image" style="width: 100px; height: 100px;">
                        </div>
                        <div class="col-md-6">
                            <img src="<?=base_url('assets/admincast/dist/assets/img/image.jpg') ?>" alt="" class="img img-thumbnail img-preview" id="img-old-image" style="width: 100px; height: 100px;">
                        </div>
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
                        "url": "<?php echo base_url('/master/desa/ajax_list') ?>",
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
    
	function shpRequired() {
		$('[name="val_the_geom"]').attr('required', 'true');
		$('[name="val_the_geom_shx"]').attr('required', 'true');
		$('[name="val_the_geom_dbf"]').attr('required', 'true');
	};

	function resetShpRequired() {
		$('[name="val_the_geom"]').removeAttr('required');
		$('[name="val_the_geom_shx"]').removeAttr('required');
		$('[name="val_the_geom_dbf"]').removeAttr('required');
	};

    function reset_form() {
        var MValid = $("#formdesa");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        resetShpRequired();
    }

    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modaldesa').modal('show');
        $('#modaldesa .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="id_desa"]').val(null);
    }

    function edit_data(id) {
        reset_form();
        $('#formdesa').valid();
        $('[name="method"]').val('update');
        $.ajax({
            type: "GET",
            url: "<?=base_url('/master/desa/get_data');?>/"+id,
            dataType: "JSON",
            success: function (response) {
                $('#modaldesa').modal('show');
                $('#modaldesa .modal-title').text('Edit Data');
                $('[name="id_desa"]').val(response.id_desa);
                $('[name="val_ds"]').val(response.ds);
				$('[name="val_kec"]').val(response.kec);
				$('[name="val_kab"]').val(response.kab);
				$('#img-old-image').attr('src', '<?=base_url('')?>/'+response.image);
				$('#img-preview-image').attr('src', '<?=base_url('assets/admincast/dist/assets/img/image.jpg')?>');
				
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
                url: "<?=base_url('/master/desa/delete_data')?>/"+id,
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
        $('#formdesa').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_the_geom: {
                
            },

			val_ds: {
                required: true,
				maxlength: 25
            },

			val_kec: {
                required: true,
				maxlength: 25
            },

			val_kab: {
                required: true,
				maxlength: 25
            },

			val_image: {
                
				maxlength: 255
            },

            },
            messages: {
				val_the_geom: {
                    
                },

				val_ds: {
                    required:'Ds harus diisi',maxlength: 'Ds Tidak Boleh Lebih dari 25 Huruf'
                },

				val_kec: {
                    required:'Kec harus diisi',maxlength: 'Kec Tidak Boleh Lebih dari 25 Huruf'
                },

				val_kab: {
                    required:'Kab harus diisi',maxlength: 'Kab Tidak Boleh Lebih dari 25 Huruf'
                },

				val_image: {
                    maxlength: 'Image Tidak Boleh Lebih dari 255 Huruf'
                },


            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = "<?=base_url('/master/desa/save_data');?>";
                var formData = new FormData($($('#formdesa'))[0]);
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
                            $('#modaldesa').modal('hide');
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
