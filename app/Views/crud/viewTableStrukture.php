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
        <h1 class="page-title">Crud Generator</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Crud</li>
            <li class="breadcrumb-item">Fields</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox small">
                    <div class="ibox-body">
                        <?=form_open(base_url('crud/result'), ['id' => 'form_crud'], ['table' => $table])?>
                        <?=csrf_field();?>
                        <div class="form-group">
                            <label for="namespace">Namespace</label>
                            <input type="text" class="form-control" name="namespace" placeholder="\Master">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="primaryKey">primaryKey</label>
                                    <select class="form-control" name="primaryKey" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                        <?php if($value->primary_key == 1): ?>
                                        <option value="<?=$value->name;?>" selected><?=$value->name;?></option>
                                        <?php else: ?>
                                        <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                        <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderBy">orderBy</label>
                                    <select class="form-control" name="orderBy" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                        <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="createdAt">createdAt</label>
                                    <select class="form-control" name="createdAt" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                        <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="updatedAt">updatedAt</label>
                                    <select class="form-control" name="updatedAt" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                        <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="deletedAt">deletedAt</label>
                                    <select class="form-control" name="deletedAt" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                        <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rbac">Kode RBAC</label>
                            <input type="number" class="form-control" name="rbac" placeholder="number">
                        </div>

                        <table class="table">
                            <tr>
                                <th>Nama Filed</th>
                                <th>Alias Field</th>
                                <th>Max Length</th>
                                <th>Form Field</th>
                                <th>Exist Keyword</th>
                            </tr>
                            <?php $no = 1; ?>
                            <?php foreach($fields as $value): ?>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="fieldName[<?=$no;?>]"
                                        value="<?=$value->name;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="fieldAlias[<?=$no;?>]"
                                        value="<?=ucwords(str_replace('_', ' ', str_replace($table.'_', '', $value->name)))?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="maxLength[<?=$no;?>]"
                                        value="<?=$value->max_length;?>">
                                </td>
                                <td>
                                    <label for="fieldTable_<?=$no;?>">
                                        <input type="checkbox" id="fieldTable_<?=$no?>" name="fieldTable[<?=$no;?>]"
                                            value="<?=$value->name;?>"> <?=$value->name;?>
                                    </label>
                                </td>
                                <td>
                                    <label for="fieldTableRemote_<?=$no?>">
                                        <input type="checkbox" id="fieldTableRemote_<?=$no?>"
                                            name="fieldTableRemote[<?=$no;?>]" value="<?=$value->name;?>"> Ya
                                    </label>
                                </td>
                            </tr>
                            <?php $no++; ?>
                            <?php endforeach ?>
                        </table>
                        <button type="submit" class="btn btn-primary">Next</button>
                        <?=form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- hide the content -->
<div id="page-content-more" class="hide"></div>

<?= $this->endSection(); ?>