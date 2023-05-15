<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<!-- PLUGINS STYLES-->
<link href="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php 
    $crudConfig = !empty($crudConfig) ? json_decode($crudConfig['crud_config']) : [];
    $fieldAlias = isset($crudConfig->fieldAlias) ? (array) $crudConfig->fieldAlias : [];
    $maxLength = isset($crudConfig->maxLength) ? (array) $crudConfig->maxLength : [];
    $fieldTable = isset($crudConfig->fieldTable) ? (array) $crudConfig->fieldTable : [];
    $fieldTableRemote = isset($crudConfig->fieldTableRemote) ? (array) $crudConfig->fieldTableRemote : [];
    $fieldAttrLabel = isset($crudConfig->fieldAttrLabel) ? (array) $crudConfig->fieldAttrLabel : [];
    $fieldType = isset($crudConfig->fieldType) ? (array) $crudConfig->fieldType : [];
    $viewTable = isset($crudConfig->viewTable) ? (array) $crudConfig->viewTable : [];
    $fieldRequired = isset($crudConfig->fieldRequired) ? (array) $crudConfig->fieldRequired : [];
?>
<div id="page-content-menu">
    <div class="page-heading">
        <h1 class="page-title">Crud Generator</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
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
                            <input type="text" class="form-control" name="namespace" placeholder="\Master" value="<?=isset($crudConfig->namespace) ? $crudConfig->namespace : '';?>">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama" value="<?=isset($crudConfig->nama) ? $crudConfig->nama : '';?>">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="orderBy">orderBy</label>
                                    <select class="form-control" name="orderBy" required>
                                        <option value="">-</option>
                                        <?php foreach($fields as $value): ?>
                                            <?php if(isset($crudConfig->orderBy)):?>
                                                <?php if($crudConfig->orderBy == $value->name):?>
                                                    <option value="<?=$value->name;?>" selected><?=$value->name;?></option>
                                                <?php else: ?>
                                                    <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                                <?php endif ?>
                                            <?php else: ?>
                                                <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="asc">Asc</label>
                                    <select class="form-control" name="asc" required>
                                        <option value="">-</option>
                                        <?php if(isset($crudConfig->asc)):?>
                                            <?php if($crudConfig->asc == 'asc'):?>
                                                <option value="asc" selected>asc</option>
                                                <option value="desc">desc</option>
                                            <?php else: ?>
                                                <option value="asc">asc</option>
                                                <option value="desc" selected>desc</option>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <option value="asc">asc</option>
                                            <option value="desc">desc</option>
                                        <?php endif ?>
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
                                            <?php if(isset($crudConfig->createdAt)):?>
                                                <?php if($crudConfig->createdAt == $value->name):?>
                                                    <option value="<?=$value->name;?>" selected><?=$value->name;?></option>
                                                <?php else: ?>
                                                    <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                                <?php endif ?>
                                            <?php else: ?>
                                                <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                            <?php endif ?>
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
                                            <?php if(isset($crudConfig->updatedAt)):?>
                                                <?php if($crudConfig->updatedAt == $value->name):?>
                                                    <option value="<?=$value->name;?>" selected><?=$value->name;?></option>
                                                <?php else: ?>
                                                    <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                                <?php endif ?>
                                            <?php else: ?>
                                                <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                            <?php endif ?>
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
                                            <?php if(isset($crudConfig->deletedAt)):?>
                                                <?php if($crudConfig->deletedAt == $value->name):?>
                                                    <option value="<?=$value->name;?>" selected><?=$value->name;?></option>
                                                <?php else: ?>
                                                    <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                                <?php endif ?>
                                            <?php else: ?>
                                                <option value="<?=$value->name;?>"><?=$value->name;?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rbac">Kode RBAC</label>
                            <input type="number" class="form-control" name="rbac" placeholder="number" value="<?=isset($crudConfig->rbac) ? $crudConfig->rbac : '';?>">
                        </div>

                        <table class="table">
                            <tr>
                                <th>Nama Filed</th>
                                <th>Alias Field</th>
                                <th>ATtr</th>
                                <th>Type</th>
                                <th>Max Length</th>
                                <th>Form Field</th>
                                <th>Datatable</th>
                                <th>Required</th>
                                <th>Exist Keyword</th>
                            </tr>
                            <?php $no = 1; ?>
                            <?php foreach($fields as $value): ?>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="fieldName[<?=$value->name;?>]"
                                        value="<?=$value->name;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="fieldAlias[<?=$value->name;?>]"
                                        value="<?=isset($fieldAlias[$value->name]) ? $fieldAlias[$value->name] : ucwords(str_replace('_', ' ', str_replace($table.'_', '', $value->name)))?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="fieldAttrLabel[<?=$value->name;?>]"
                                        value="<?=isset($fieldAttrLabel[$value->name]) ? $fieldAttrLabel[$value->name] : '';?>">
                                </td>
                                <td>
                                    <?php $type = isset($fieldType[$value->name]) ? $fieldType[$value->name] : '';?>
                                    <?=form_dropdown('fieldType['.$value->name.']', [
                                        'text' => 'text',
                                        'number' => 'number',
                                        'textarea' => 'textarea',
                                        'koordinate' => 'koordinate',
                                        'rupiah' => 'rupiah',
                                        'image' => 'image',
                                        'geometry' => 'geometry',
                                        'date' => 'date'
                                    ], $type, ['class' => 'form-control'])?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="maxLength[<?=$value->name;?>]"
                                        value="<?= isset($maxLength[$value->name]) ? $maxLength[$value->name] : $value->max_length;?>">
                                </td>
                                <td>
                                    <label for="fieldTable_<?=$value->name;?>">
                                        <input type="checkbox" id="fieldTable_<?=$value->name?>" name="fieldTable[<?=$value->name;?>]"
                                            value="<?=$value->name;?>" <?=isset($fieldTable[$value->name]) ? 'checked' : '';?>> <?=$value->name;?>
                                    </label>
                                </td>
                                <td>
                                    <label for="viewTable_<?=$value->name;?>">
                                        <input type="checkbox" id="viewTable_<?=$value->name?>" name="viewTable[<?=$value->name;?>]"
                                            value="<?=$value->name;?>" <?=isset($viewTable[$value->name]) ? 'checked' : '';?>> <?=$value->name;?>
                                    </label>
                                </td>
                                <td>
                                    <label for="fieldRequired_<?=$value->name;?>">
                                        <input type="checkbox" id="fieldRequired_<?=$value->name?>" name="fieldRequired[<?=$value->name;?>]"
                                            value="<?=$value->name;?>" <?=isset($fieldRequired[$value->name]) ? 'checked' : '';?>> <?=$value->name;?>
                                    </label>
                                </td>
                                <td>
                                    <label for="fieldTableRemote_<?=$value->name?>">
                                        <input type="checkbox" id="fieldTableRemote_<?=$value->name?>"
                                            name="fieldTableRemote[<?=$value->name;?>]" value="<?=$value->name;?>" <?=isset($fieldTableRemote[$value->name]) ? 'checked' : '';?>> Ya
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