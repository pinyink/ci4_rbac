<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">

<style>
    #jstree ul {
        list-style: none;
    }

    #jstree ul li {
        padding-top: 20px;
    }

    #jstree ul li ul li {
        padding-top: 5px;
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
            <li class="breadcrumb-item">Policy</li>
            <li class="breadcrumb-item">Menu List</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox small">
                    <div class="ibox-head">
                        <div class="ibox-title"></div>
                        <div class="ibox-tools">
                            <a href="javascript:;" onclick="modalAddOpen()" data-toggle="tooltip" data-placement="top" title="Add Data"><i class="fa fa-plus-square"></i></a>
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <div id="jstree">
                            <ul>
                                <?php foreach($menu as $key => $vMenu): ?>
                                    <li id="<?=$vMenu->menu_id;?>"><?=$vMenu->menu_id.' - '.$vMenu->menu_desc;?>
                                        <ul id="ul_<?=$vMenu->menu_id;?>">
                                        <li class="pb-2">
                                            <div id="div_<?=$vMenu->menu_id;?>" style="display: none;">
                                                <form class="menuForm">
                                                    <input type="hidden" name="menu_id" value="<?=$vMenu->menu_id;?>">
                                                    <div class="form-group row">
                                                        <div class="input-group input-group-sm col-md-3">
                                                            <input class="form-control text_<?=$vMenu->menu_id;?>" type="text" placeholder="Nama Menu Akses" name="nama_menu" autocomplete="off">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i></button>
                                                                <button class="btn btn-danger" type="button" onclick="closeForm(<?=$vMenu->menu_id;?>)"><i class="fa fa-close"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="button_<?=$vMenu->menu_id;?>">
                                                <button onclick="lihatForm(<?=$vMenu->menu_id;?>)" class="btn btn-xs btn-outline-success"><i class="fa fa-plus-square-o"></i> Tambah</button>
                                            </div>
                                        </li>
                                        <?php foreach($data as $kData => $vData): ?>
                                            <?php if($vMenu->menu_id == $vData['menu_id']): ?>
                                            <li id="<?=$vMenu->menu_id.'_'.$vData['menu_id'];?>">
                                                <label class="ui-checkbox">
                                                    <input type="checkbox" name="<?=$vMenu->menu_id.'_'.$vData['menu_akses_id'];?>">
                                                    <span class="input-span"></span><?=$vData['menu_akses_id'].' - '.$vData['menu_akses_desc'];?></label>
                                            </li>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                        </ul>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('js'); ?>
<script src="<?= base_url(); ?>/assets/alertifyjs/alertify.min.js"></script>
<script type="text/javascript">
    var policy_Id;
    
    function lihatForm(id) {
        $('#div_'+id).show();
        $('#button_'+id).hide();
    }

    function closeForm(id) {
        $('.text_'+id).val('');
        $('#div_'+id).hide();
        $('#button_'+id).show();
    }

    $('.menuForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            'url': '<?=base_url();?>/admin/policy/saveSubMenu',
            'type': 'POST',
            'dataType': 'JSON',
            'data' : data
        })
        .done(function(response){
            if(response.errorCode == 1) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                    console.log('dismissed');
                });
                $('#ul_'+response.data.menu_id).append('<li id="'+response.data.menu_id+'_'+response.data.menu_akses_id+'"><label class="ui-checkbox"><input type="checkbox" name="'+response.data.menu_id+'_'+response.data.menu_akses_id+'"><span class="input-span"></span>'+response.data.menu_akses_id+' - '+response.data.menu_akses_desc+'</label></li>');
                closeForm(response.data.menu_id);
            }
        })
        .fail(function(jqXHR){
            console.log(jqXHR.responseText);
        });
    });

    function remove_policy(menu_id, menu_akses_id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/removePolicy',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    menu_id: menu_id,
                    menu_akses_id: menu_akses_id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
                tableMenu.ajax.reload(null, false);
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }

    function add_policy(menu_id, menu_akses_id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/addPolicy',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    menu_id: menu_id,
                    menu_akses_id: menu_akses_id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
                tableMenu.ajax.reload(null, false);
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }
</script>
<?= $this->endSection(); ?>