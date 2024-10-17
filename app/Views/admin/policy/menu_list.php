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
                        <div class="ibox-title">Role Menu Akses</div>
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
                                    <li id="<?=$vMenu['menu_id'];?>"><?=$vMenu['menu_id'].' - '.$vMenu['menu_desc'];?>
                                        <ul id="ul_<?=$vMenu['menu_id'];?>">
                                        <?php foreach($data as $kData => $vData): ?>
                                            <?php if($vMenu['menu_id'] == $vData['menu_id']): ?>
                                            <li id="<?=$vMenu['menu_id'].'_'.$vData['menu_id'];?>">
                                                <label class="ui-checkbox">
                                                    <input type="checkbox" class="checkbox" name="<?=$vMenu['menu_id'].'_'.$vData['menu_akses_id'];?>" value="<?=$vData['akses_id'];?>" <?=$vData['check']=='Y'?'checked':'';?>>
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
    var policy_Id = '<?=$policyId;?>';

    $( "input.checkbox" ).on( "click", function() {
        var val = $(this).val();
        if ($( this ).is(":checked")) {
            add_policy(val);
        } else {
            remove_policy(val);
        }
    });

    function remove_policy(akses_id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/removePolicy',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    <?=csrf_token();?>: '<?=csrf_hash()?>',
                    akses_id: akses_id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }

    function add_policy(akses_id) {
        $.ajax({
                'url': '<?= base_url(); ?>/admin/policy/addPolicy',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'data': {
                    <?=csrf_token();?>: '<?=csrf_hash()?>',
                    akses_id: akses_id,
                    policy_id: policy_Id
                }
            })
            .done(function(data) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            });
    }
</script>
<?= $this->endSection(); ?>