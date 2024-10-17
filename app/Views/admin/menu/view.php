<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<style>
    div.list-group {
        padding-left: 2.5em;
    }
</style>
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
            <li class="breadcrumb-item">Menu</li>
            <li class="breadcrumb-item">Lihat</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="ibox small">
                    <div class="ibox-head">
                        <div class="ibox-title">Menu dan Menu Akses</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <a href="javascript:;" class="btn btn-sm btn-primary" onclick="tambahMenu()"><i class="fa fa-plus-square-o"></i>
                            Tambah</a>
                        <ul class="list-group list-group-bordered" id="listMenu">
                            <?php foreach($menu as $key => $vMenu): ?>
                            <li class="list-group-item list-group-item-info mt-3" id="menu_<?=$vMenu->menu_id?>">
                                <span id="span_menu_<?=$vMenu->menu_id;?>"><?=$vMenu->menu_desc;?></span>
                                <a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top"
                                    title="Tambah Role" onclick="tambahMenuAkses(<?=$vMenu->menu_id;?>)"><i class="fa fa-plus-square-o"></i></a>
                                <a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top"
                                    title="Edit" onclick="editMenu(<?=$vMenu->menu_id;?>)"><i class="fa fa-edit"></i></a>
                            </li>
                            <div class="list-group" id="list_group_<?=$vMenu->menu_id;?>">
                                <?php foreach($data as $kData => $vData): ?>
                                <?php if($vMenu->menu_id == $vData['menu_id']): ?>
                                <li class="list-group-item mt-1"
                                    id="menu_akses_<?=$vData['akses_id'];?>">
                                    <span id="span_menu_akses_<?=$vData['akses_id'];?>"><?=$vData['menu_akses_id'].'. '.$vData['menu_akses_desc'];?></span>
                                    <a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top"
                                        title="Edit" onclick="editMenuAkses(<?=$vData['akses_id']?>)"><i class="fa fa-edit"></i></a>
                                </li>
                                <?php endif ?>
                                <?php endforeach ?>
                            </div>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <div class="ibox small">
                    <div class="ibox-head">
                        <div class="ibox-title" id="titleMenu">Form Menu</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form id="formMenu">
                            <input type="hidden" name="idMenu" value="">
                            <div class="row">
                                <div class="col-sm-6 form-group" id="divMenuAkses">
                                    <label id="labelNamaMenuAkses">Kode Menu Akses</label>
                                    <input class="form-control" type="text" name="kodeMenuAkses">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label id="labelNamaMenu">Nama Menu</label>
                                    <input class="form-control" type="text" name="namaMenu">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" type="submit" id="btnMenu">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
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
<script type="text/javascript">
    var saveMethod;

    $(document).ready(function(){
        $('#formMenu :input').prop('disabled', 'true');
        $('#divMenuAkses').hide();
    });

    function tambahMenu(){
        resetForm();
        $('#titleMenu').text('Form Tambah Menu');
        $('#labelNamaMenu').text('Nama Menu');
        $('#btnMenu').text('Simpan');
        $('#modalMenu').modal('show');
        saveMethod = 'tambah_menu';
        $('#formMenu :input').prop('disabled', false);
    }

    function editMenu(id){
        resetForm();
        saveMethod = 'edit_menu';
        $('#formMenu :input').prop('disabled', false);
        $.ajax({
            'url': '<?=base_url();?>/admin/menu/getMenu/'+id,
            'type': 'get',
            'dataType': 'JSON'
        })
        .done(function(response){
            $('[name="namaMenu"]').val(response.data.menu_desc);
            $('[name="idMenu"]').val(response.data.menu_id);
            $('#modalMenu').modal('show');
            $('#titleMenu').text('Form Edit Menu');
            $('#labelNamaMenu').text('Nama Menu');
            $('#btnMenu').text('Update');
        })
        .fail(function(jqXHR){
            console.log(jqXHR.responseText);
        })
        .always();
    }

    function tambahMenuAkses(id) {
        $('#titleMenu').text('Form Tambah Menu Akses');
        $('#labelNamaMenu').text('Nama Menu Akses');
        $('#btnMenu').text('tambah');
        saveMethod = 'tambah_menu_akses';
        $('#formMenu :input').prop('disabled', false);
        $('[name="idMenu"]').val(id);
        $('#divMenuAkses').show();
        $('#modalMenu').modal('show');
    }

    function editMenuAkses(id){
        saveMethod = 'edit_menu_akses';
        $('#formMenu :input').prop('disabled', false);
        $.ajax({
            'url': '<?=base_url();?>/admin/menu/getMenuAkses/'+id,
            'type': 'get',
            'dataType': 'JSON'
        })
        .done(function(response){
            $('[name="namaMenu"]').val(response.data.menu_akses_desc);
            $('[name="idMenu"]').val(response.data.akses_id);
            $('[name="kodeMenuAkses"]').val(response.data.menu_akses_id);
            $('#divMenuAkses').show();
            $('#modalMenu').modal('show');
            $('#titleMenu').text('Form Edit Menu Akses');
            $('#labelNamaMenu').text('Nama Menu Akses');
            $('#btnMenu').text('Update');
        })
        .fail(function(jqXHR){
            console.log(jqXHR.responseText);
        })
        .always();
    }

    function resetForm() {
        $('#formMenu')[0].reset();
        $('#formMenu :input').prop('disabled', 'true');
        $('#divMenuAkses').hide();
    }

    $(function() {
        $('#formMenu').validate({
            errorClass: "help-block",
            rules: {
                namaMenu: {
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
                var dataString = $('#formMenu').serialize() + '&<?=csrf_token()?>=' + '<?=csrf_hash();?>';
                var url;
                if (saveMethod == 'tambah_menu') {
                    url = '<?= base_url(); ?>/admin/menu/saveMenu';
                } else if (saveMethod == 'edit_menu') {
                    url = '<?= base_url(); ?>/admin/menu/updateMenu';
                } else if(saveMethod == 'edit_menu_akses') {
                    url = '<?= base_url(); ?>/admin/menu/updateMenuAkses';
                } else if(saveMethod == 'tambah_menu_akses') {
                    url = '<?= base_url(); ?>/admin/menu/tambahMenuAkses';
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
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                        console.log('dismissed');
                    });
                    if (data.errorCode == 1) {
                        resetForm();
                        if (saveMethod == 'tambah_menu') {
                            $('ul.list-group').append('<li class="list-group-item list-group-item-info mt-3" id="menu_'+data.menu_id+'"><span id="span_menu_'+data.menu_id+'">'+data.menu_desc+'</span><a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top" title="Tambah Role" onclick="tambahRole('+data.menu_id+')"><i class="fa fa-plus-square-o"></i></a><a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top" title="Edit" onclick="editMenu('+data.menu_id+')"><i class="fa fa-edit"></i></a></li><div class="list-group" id="list_group_'+data.menu_id+'"></div>');
                        } else if (saveMethod == 'edit_menu') {
                            $('#span_menu_'+data.menu_id).text(data.menu_desc);
                        } else if(saveMethod == 'edit_menu_akses') {
                            $('#span_menu_akses_'+data.akses_id).text(data.menu_akses_id+'. '+data.menu_akses_desc);
                        } else if (saveMethod == 'tambah_menu_akses') {
                            $('#list_group_'+data.menu_id).append('<li class="list-group-item mt-1" id="menu_akses_'+data.akses_id+'"><span id="span_menu_akses_'+data.akses_id+'">'+data.menu_akses_id+'. '+data.menu_akses_desc+'</span><a class="btn btn-sm float-right" data-toggle="tooltip" data-placement="top" title="Edit" onclick="editMenuAkses('+data.akses_id+')"><i class="fa fa-edit"></i></a></li>');
                        }
                        $('#modalMenu').modal('hide');
                    }
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