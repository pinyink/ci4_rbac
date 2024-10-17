
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
    <h1 class="page-title">Crid Detail</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;"><?=$crid['title'];?></a>
        </li>        
        <li class="breadcrumb-item active">Crid Detail</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-body">
                    <a href="<?=base_url('crid/'.$crid['id'].'/generate_crud')?>" class="btn btn-primary" target="_blank"><i class="fa fa-building-o"></i> Generate Crud</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Crid Detail</div>
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
									<th style="width: 25%;">Name Field</th>
									<th style="width: 25%;">Name Alias</th>
									<th style="width: 25%;">Name Type</th>
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

<div class="modal fade" id="modalcriddetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <?=form_open('', ['id' => 'formcriddetail'], ['id' => '', 'method' => '', 'val_crid_id' => $crid['id']]);?>
            <?=csrf_field();?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=form_label('Name Field');?>
                                <?=form_input('val_name_field', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=form_label('Name Alias');?>
                                <?=form_input('val_name_alias', '', ['class' => 'form-control'], 'text');?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=form_label('Name Type');?>
                                <?=form_dropdown('val_name_type', [
                                        '' => '-',
                                        'text' => 'text',
                                        'number' => 'number',
                                        'textarea' => 'textarea',
                                        // 'koordinate' => 'koordinate',
                                        'rupiah' => 'rupiah',
                                        'image' => 'image',
                                        'pdf' => 'pdf',
                                        'date' => 'date',
                                        'join' => 'join'
                                    ], '', ['class' => 'form-control']);?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=form_label('Min')?>
                                <?=form_input('val_field_min', '', ['class' => 'form-control'], 'number')?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=form_label('Max')?>
                                <?=form_input('val_field_max', '', ['class' => 'form-control'], 'number')?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label><input type="checkbox" name="val_field_form" id="val_field_form" value="1"> Field Form</label>
                        </div>
                        <div class="col-3">
                            <label><input type="checkbox" name="val_field_database" id="val_field_database" value="1"> Field Database</label>
                        </div>
                        <div class="col-3">
                            <label><input type="checkbox" name="val_field_required" id="val_field_required" value="1"> Field Required</label>
                        </div>
                        <div class="col-3">
                            <label><input type="checkbox" name="val_field_unique" id="val_field_unique" value="1"> Field Unique</label>
                        </div>
                    </div>
                    <div class="row mt-3" id="divjoin">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?=form_label('Join')?>
                                <?=form_dropdown('val_join_table', dropdown($table, 'id', 'table'), '', ['class' => 'form-control'])?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?=form_label('Dropdown')?>
                                <?=form_dropdown('val_join_field', [], '', ['class' => 'form-control'])?>
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
                        "url": "<?php echo base_url('/criddetail/ajax_list?id='.$crid['id']) ?>",
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
        $('#divjoin').hide();
    });

    $('[name="val_name_type"]').change(function (e) { 
        var v = this.value;
        if (v == 'join') {
            $('#divjoin').show();
        } else {
            $('#divjoin').hide();
        }
    });

    $('[name="val_join_table"]').change(function (e) { 
        $.ajax({
            type: "GET",
            url: "<?=base_url('criddetail')?>/"+$('[name="val_join_table"]').val()+'/by_crid_id',
            dataType: "json",
            success: function (response) {
                $('[name="val_join_field"]').empty();
                $('[name="val_join_field"]').append('<option value="">-</option>');
                $.each(response, function (indexInArray, valueOfElement) { 
                    $('[name="val_join_field"]').append('<option value="'+valueOfElement.id+'">'+valueOfElement.name_field+'</option>');
                });
            }
        });
    });
    

    function reset_form() {
        var MValid = $("#formcriddetail");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        
    }

    <?php if(enforce(1, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalcriddetail').modal('show');
        $('#modalcriddetail .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="id"]').val(null);
        $('#formcriddetail .form-control').removeClass('form-view-detail');
        $('#formcriddetail .form-control').prop('disabled', false);
        $('#formcriddetail button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(1, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formcriddetail').valid();
        $('[name="method"]').val('update');
        $('#formcriddetail .form-control').removeClass('form-view-detail');
        $('#formcriddetail .form-control').prop('disabled', false);
        $('#formcriddetail button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/criddetail');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalcriddetail').modal('show');
                $('#modalcriddetail .modal-title').text('Edit Data');
                $('[name="id"]').val(response.id);
                $('[name="val_crid_id"]').val(response.crid_id);
				$('[name="val_name_field"]').val(response.name_field);
				$('[name="val_name_alias"]').val(response.name_alias);
				$('[name="val_name_type"]').val(response.name_type);
                if (response.field_form == 1) {
                    $('[name="val_field_form"]').prop('checked', true);
                }
                if(response.field_database == 1) {
                    $('[name="val_field_database"]').prop('checked', true);
                }
                if (response.field_required == 1) {
                    $('[name="val_field_required"]').prop('checked', true);
                }
                if (response.field_unique == 1) {
                    $('[name="val_field_unique"]').prop('checked', true);
                }
                
				$('[name="val_field_min"]').val(response.field_min);
				$('[name="val_field_max"]').val(response.field_max);

                var v = response.name_type;
                if (v == 'join') {
                    $('#divjoin').show();
                    $('[name="val_join_table"]').val(response.join_table);
                    $.ajax({
                        type: "GET",
                        url: "<?=base_url('criddetail')?>/"+$('[name="val_join_table"]').val()+'/by_crid_id',
                        dataType: "json",
                        success: function (r) {
                            $('[name="val_join_field"]').empty();
                            $('[name="val_join_field"]').append('<option value="">-</option>');
                            $.each(r, function (indexInArray, valueOfElement) { 
                                $('[name="val_join_field"]').append('<option value="'+valueOfElement.id+'">'+valueOfElement.name_field+'</option>');
                            });
                            $('[name="val_join_field"]').val(response.join_field);
                        }
                    });
                } else {
                    $('#divjoin').hide();
                }
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
                url: "<?=base_url('/criddetail')?>/"+id+'/delete_data',
                data: {'<?=csrf_token()?>' : '<?=csrf_hash()?>'},
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
        $('#formcriddetail').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_crid_id: {
                required: true,
				maxlength: 11
            },

			val_name_field: {
                required: true,
				remote: {
                    url: "<?=base_url('/criddetail/name_field_exist'); ?>",
                    type: "post",
                    data: {
                        name_field: function() {
                            return $('[name="val_name_field"]').val();
                        },
                        id: function() {
                            return $('[name="id"]').val();
                        },
                        <?=csrf_token()?>: '<?=csrf_hash();?>',
                        crid_id: function() {
                            return $('[name="val_crid_id"]').val();
                        },
                    },
                },

				maxlength: 64
            },

			val_name_alias: {
                required: true,
				maxlength: 64
            },

			val_name_type: {
                required: true,
				maxlength: 11
            },

			val_field_form: {
                
				maxlength: 11
            },

			val_field_database: {
                
				maxlength: 11
            },

			val_field_required: {
                
				maxlength: 11
            },

			val_field_settings: {
                
            },

            },
            messages: {
				val_crid_id: {
                    required:'Crid Id harus diisi',maxlength: 'Crid Id Tidak Boleh Lebih dari 11 Huruf'
                },

				val_name_field: {
                    required:'Name Field harus diisi',remote: 'Name Field sudah Ada, Tidak bisa di Input',maxlength: 'Name Field Tidak Boleh Lebih dari 64 Huruf'
                },

				val_name_alias: {
                    required:'Name Alias harus diisi',maxlength: 'Name Alias Tidak Boleh Lebih dari 64 Huruf'
                },

				val_name_type: {
                    required:'Name Type harus diisi',maxlength: 'Name Type Tidak Boleh Lebih dari 11 Huruf'
                },

				val_field_form: {
                    maxlength: 'Field Form Tidak Boleh Lebih dari 11 Huruf'
                },

				val_field_database: {
                    maxlength: 'Field Database Tidak Boleh Lebih dari 11 Huruf'
                },

				val_field_required: {
                    maxlength: 'Field Required Tidak Boleh Lebih dari 11 Huruf'
                },

				val_field_settings: {
                    
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
                    url = "<?=base_url('/criddetail/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/criddetail/save_data');?>";
                }
                var formData = new FormData($($('#formcriddetail'))[0]);
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
                            $('#modalcriddetail').modal('hide');
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
