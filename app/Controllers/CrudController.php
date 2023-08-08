<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\CrudModel;

class CrudController extends BaseController
{
    function __construct()
    {
        helper(['form', 'FormCustom']);

    }

    public function index()
    {
        $db = db_connect();
        $tables = $db->listTables();

        $tema = new Tema();
        $tema->loadTema('crud/viewTable', ['tables' => $tables]);
    }

    public function table()
    {
        $request = \Config\Services::request();
        $table = $request->getPost('table');
        
        $db = db_connect();
        $fields = $db->getFieldData($table);

        $crudModel = new CrudModel();
        $crudConfig = $crudModel->find($table);

        $tema = new Tema();
        $tema->loadTema('crud/viewTableStrukture', ['table' => $table, 'fields' => $fields, 'crudConfig' => $crudConfig]);
    }

    public function result()
    {
        $request = \Config\Services::request();
        $namespace =  $request->getPost('namespace') == null ? '' : $request->getPost('namespace');
        $nama = $request->getPost('nama') == null ? '' : $request->getPost('nama');

        $table = $request->getPost('table');
        $primaryKey = $request->getPost('primaryKey');
        $createdAt = $request->getPost('createdAt');
        $updatedAt = $request->getPost('updatedAt');
        $deletedAt = $request->getPost('deletedAt');
        $orderBy = $request->getPost('orderBy');
        $asc = $request->getPost('asc');
        $rbac = $request->getPost('rbac');

        $fieldTable = $request->getPost('fieldTable');
        $fieldAlias = $request->getPost('fieldAlias');
        $fieldTableRemote = $request->getPost('fieldTableRemote');
        $maxLength = $request->getPost('maxLength');
        $fieldAttrLabel =  $this->request->getPost('fieldAttrLabel');
        $fieldType = $this->request->getPost('fieldType');
        $viewTable = $this->request->getPost('viewTable');
        $fieldRequired = $this->request->getPost('fieldRequired');

        $dataSave = [
            'namespace' => $namespace,
            'nama' => $nama,
            'table' => $table,
            'primaryKey' => $primaryKey,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
            'deletedAt' => $deletedAt,
            'orderBy' => $orderBy,
            'asc' => $asc,
            'rbac' => $rbac,
            'fieldTable' => $fieldTable,
            'fieldAlias' => $fieldAlias,
            'fieldTableRemote' => $fieldTableRemote,
            'maxLength' => $maxLength,
            'fieldAttrLabel' => $fieldAttrLabel,
            'fieldType' => $fieldType,
            'viewTable' => $viewTable,
            'fieldRequired' => $fieldRequired
        ];
        $crudModel = new CrudModel();
        $findCrudTable = $crudModel->find($table);
        if (empty($findCrudTable)) {
            $crudModel->insert(['crud_table' => $table, 'crud_config' => json_encode($dataSave)]);
        } else {
            $crudModel->update($table, ['crud_table' => $table, 'crud_config' => json_encode($dataSave)]);
        }

        $namaController = str_replace(' ', '', ucwords(strtolower($nama))).'Controller';
        $url = str_replace(' ', '', strtolower($nama));

        $routeName = strtolower(str_replace('\\', '/', $namespace)).'/'.$url;
        $routeExistField = "";
        foreach ($fieldTable as $key => $value) {
            if (isset($fieldTableRemote[$key])) {
                $routeExistField .= "\n\t\$routes->post('".$value."_exist', '".$namaController."::".strtolower(str_replace('_', '', $value))."Exist', ['filter' => 'auth:N,".$rbac.",2']);";
            }
        }
$route = "
\$routes->group('".$routeName."', ['namespace' => 'App\Controllers".$namespace."'], static function(\$routes) {
    \$routes->get('/', '".$namaController."::index', ['filter' => 'auth:Y,".$rbac.",1']);
    \$routes->post('ajax_list', '".$namaController."::ajaxList', ['filter' => 'auth:N,".$rbac.",1']);
    \$routes->post('save_data', '".$namaController."::saveData', ['filter' => 'auth:N,".$rbac.",2']);
    \$routes->post('update_data', '".$namaController."::saveData', ['filter' => 'auth:N,".$rbac.",3']);
    \$routes->get('(:num)/get_data', '".$namaController."::getData/$1', ['filter' => 'auth:N,".$rbac.",1']);
    \$routes->delete('(:num)/delete_data', '".$namaController."::deleteData/$1', ['filter' => 'auth:N,".$rbac.",4']);".$routeExistField."
});";

    echo "<pre>".$route."</pre>";

$namaModel = str_replace(' ', '', ucwords(strtolower($nama))).'Model';
$columnSearch = '';
$allowedFields = '';
$selectFields = '';
$no = 1;
foreach ($viewTable as $key => $value) {
    if ($no == 1) {
        $columnSearch .= "'a.".$value."'";
        $selectFields .= "a.".$value."";
    } else {
        $columnSearch .= ", 'a.".$value."'";
        $selectFields .= ", a.".$value."";
    }
    $no++;
}
// allowed field
$no = 1;
foreach ($fieldTable as $key => $value) {
    if ($no == 1) {
        $allowedFields .= "'".$value."'";
    } else {
        $allowedFields .= ", '".$value."'";
    }
    $no++;
}
$model = "@?php

namespace App\Models".$namespace.";

use CodeIgniter\Model;

class ".$namaModel." extends Model
{
    protected \$DBGroup          = 'default';
    protected \$table            = '".$table."';
    protected \$primaryKey       = '".$primaryKey."';
    protected \$useAutoIncrement = true;
    protected \$insertID         = 0;
    protected \$returnType       = 'array';
    protected \$useSoftDeletes   = true;
    protected \$protectFields    = true;
    protected \$allowedFields    = [".$allowedFields."];

    // Dates
    protected \$useTimestamps = true;
    protected \$dateFormat    = 'datetime';
    protected \$createdField  = '".$createdAt."';
    protected \$updatedField  = '".$updatedAt."';
    protected \$deletedField  = '".$deletedAt."';

    // Validation
    protected \$validationRules      = [];
    protected \$validationMessages   = [];
    protected \$skipValidation       = false;
    protected \$cleanValidationRules = true;

    // Callbacks
    protected \$allowCallbacks = true;
    protected \$beforeInsert   = [];
    protected \$afterInsert    = [];
    protected \$beforeUpdate   = [];
    protected \$afterUpdate    = [];
    protected \$beforeFind     = [];
    protected \$afterFind      = [];
    protected \$beforeDelete   = [];
    protected \$afterDelete    = [];
    public \$column_order  = array(null, null, ".$columnSearch.");
    public \$column_search = array(".$columnSearch.");
    public \$order         = array('a.".$orderBy."' => '".$asc."');

    private \$request = '';
    private \$dt;
    private \$where = [];
    
    public function setWhere(\$where = [])
    {
        \$this->where = \$where;
    }

    public function setRequest(\$request)
    {
        \$this->request = \$request;
    }

    public function initDatatables()
    {
        \$this->dt = \$this->db->table(\$this->table . ' a');
    }

    private function _getDatatablesQuery()
    {
        \$this->dt->select('a.".$primaryKey.", ".$selectFields."');
        \$this->dt->where(\$this->deletedField, null);
        \$this->dt->where(\$this->where);
        \$i = 0;
        foreach (\$this->column_search as \$item) {
            if (isset(\$this->request->getPost('search')['value'])) {
                if (\$i === 0) {
                    \$this->dt->groupStart();
                    \$this->dt->like(\$item, \$this->request->getPost('search')['value']);
                } else {
                    \$this->dt->orLike(\$item, \$this->request->getPost('search')['value']);
                }
                if (count(\$this->column_search) - 1 == \$i) {
                    \$this->dt->groupEnd();
                }
            }
            \$i++;
        }
        if (\$this->request->getPost('order')) {
            \$this->dt->orderBy(\$this->column_order[\$this->request->getPost('order')['0']['column']], \$this->request->getPost('order')['0']['dir']);
        } elseif (isset(\$this->order)) {
            \$order = \$this->order;
            \$this->dt->orderBy(key(\$order), \$order[key(\$order)]);
        }
    }

    public function getDatatables()
    {
        \$this->initDatatables();
        \$this->_getDatatablesQuery();
        if (\$this->request->getPost('length') != -1) {
            \$this->dt->limit(\$this->request->getPost('length'), \$this->request->getPost('start'));
        }
        \$query = \$this->dt->get();
        return \$query->getResult();
    }

    public function countFiltered()
    {
        \$this->initDatatables();
        \$this->_getDatatablesQuery();
        return \$this->dt->countAllResults();
    }

    public function countAll()
    {
        \$tblStorage = \$this->db->table(\$this->table);
        return \$tblStorage->countAllResults();
    }
}
";
    echo "<pre>".$model."</pre>";

    // controller
    $modelVariable = '';
        $_model = explode(" ", $nama);
        $no = 0;
        if (count($_model) == 0) {
            $modelVariable = strtolower($_model[0]).'Model';
        } else {
            foreach ($_model as $key => $value) {
                if ($no == 0) {
                    $modelVariable .= strtolower($_model[0]);
                } else {
                    $modelVariable .= ucwords($_model[$no]);
                }
                $no++;
            }
            $modelVariable .= 'Model';
        }

        $functionExists = "";
        foreach ($fieldTable as $key => $value) {
            if (isset($fieldTableRemote[$key])) {
$functionExists .= "public function ".strtolower(str_replace("_", "", $value))."Exist()
    {
        \$".$modelVariable." = new ".$namaModel."();
        \$".$primaryKey." = \$this->request->getPost('".$primaryKey."');
        \$".$value." = \$this->request->getPost('".$value."');
        \$query = \$".$modelVariable."->where(['".$primaryKey." !=' => \$".$primaryKey.", '".$value."' => \$".$value."])->first();
        if (!empty(\$query)) {
            return \$this->response->setJSON(false);
        }
        return \$this->response->setJSON(true);
    }";
            }
        }

        $rowFields = '';
        $fieldInserts = '';
        $fieldImg = '';
        $formValidation = '';
        $validationImg = '';
        foreach ($viewTable as $key => $value) {
            if (in_array($fieldType[$key], ['rupiah'])) {
                $rowFields .= "\n\t\t\t"."\$row[] = number_format(\$list->".$value.", 0, '.', ',');";
            } else if (in_array($fieldType[$key], ['date'])) {
                $rowFields .= "\n\t\t\t"."\$row[] = date('d-m-Y', strtotime(\$list->".$value."));";
            } else {
                $rowFields .= "\n\t\t\t"."\$row[] = \$list->".$value.";";
            }
            
        }
        $validationRequired = '';
        $viewDetail = '';
        $viewArray = '';
        $useFunction = '';
        $ifGeometryEmpty = '';
        $no = 1;
        foreach ($fieldTable as $key => $value) {
            if (in_array($fieldType[$key], ['rupiah'])) {
                $fieldInserts .= "\n\t\t\$data['".$value."'] = \$this->request->getPost('val_".$value."') == null ? null : str_replace('.', '', \$this->request->getPost('val_".$value."'));";
            } else if(in_array($fieldType[$key], ['image'])) {
                $fieldImg .= "\n\t\t\$img".$value." = \$this->request->getFile('val_".$value."');";
                
                $formValidation .= "\n\t\tif (!empty(\$_FILES['val_".$value."']['name'])) {\n\t\t\t\$validation['val_".$value."'] = 'uploaded[val_".$value."]'\n\t\t\t. '|is_image[val_".$value."]'\n\t\t\t. '|mime_in[val_".$value.",image/jpg,image/jpeg,image/gif,image/png,image/webp]'\n\t\t\t. '|max_size[val_".$value.",2048]';\n\t\t}";

                $validationImg .= "\n\t\t\tif (!empty(\$_FILES['val_".$value."']['name'])) {\n\t\t\t\t\$type = \$img".$value."->getClientMimeType();\n\t\t\t\t\$message .= '<li>'.\$img".$value."->getErrorString() . '(' . \$img".$value."->getError() . ' Type File ' . \$type . ' )</li>';\n\t\t\t}";

                $fieldInserts .= "\n\t\tif (!empty(\$_FILES['val_".$value."']['name'])) {\n\t\t\t\$th = date('Y') . '/' . date('m').'/'.date('d');\n\t\t\t\$path = 'uploads".$routeName."/';\n\t\t\t\$_dir = \$path . \$th;\n\t\t\t\$dir = ROOTPATH.'public/' . \$path . \$th;\n\t\t\tif (!file_exists(\$dir)) {\n\t\t\t\tmkdir(\$dir, 0777, true);\n\t\t\t}\n\t\t\t\$newName = \$img".$value."->getRandomName();\n\t\t\t\$img".$value."->move(\$dir, \$newName);\n\t\t\t\$data['".$value."'] = \$_dir.'/'.\$newName;\n\t\t}";
            } else if(in_array($fieldType[$key], ['geometry'])) {
                $fieldInserts .= "\n\t\tif (!empty(\$_FILES['val_".$value."']['name'])) {\n\t\t\t\$uniqId = uniqid();\n\t\t\t\$th = date('Y') . '/' . date('m').'/'.date('d').'/'.\$uniqId;\n\t\t\t\$path = 'uploads".$routeName."';\n\t\t\t\$_dir = \$path . \$th;\n\t\t\t\$dir = ROOTPATH.'public/' . \$path . \$th;\n\t\t\tif (!file_exists(\$dir)) {\n\t\t\t\tmkdir(\$dir, 0777, true);\n\t\t\t\t\$create = fopen(\$dir.'/index.php', \"w\") or die(\"Change your permision folder for application and harviacode folder to 777\");\n\t\t\t\tfwrite(\$create, '<h1>ACCESS DENIED</h1>');\n\t\t\t\tfclose(\$create);\n\t\t\t}\n\t\t\t\$fileShp = \$this->request->getFile('val_".$value."');\n\t\t\t\$fileShp->move(\$dir, \$uniqId.'.shp');\n\t\t\t\$fileShx = \$this->request->getFile('val_".$value."_shx');\n\t\t\t\$fileShx->move(\$dir, \$uniqId.'.shx');\n\t\t\t\$fileDbf = \$this->request->getFile('val_".$value."_dbf');\n\t\t\t\$fileDbf->move(\$dir, \$uniqId.'.dbf');\n\t\t\t\$Shapefile = new ShapefileReader(\$dir.'/'.\$uniqId.'.shp');\n\t\t\t\$jsonData = '';\n\t\t\twhile (\$Geometry = \$Shapefile->fetchRecord()) {\n\t\t\t\t\$jsonData = \$Geometry->getWKT();\n\t\t\t}\n\t\t\t\$data['".$value."'] = new RawSql(\"ST_GeomFromText('\".\$jsonData.\"')\");\n\t\t}";
            } else if (in_array($fieldType[$key], ['date'])) {
                $fieldInserts .= "\n\t\t\$data['".$value."'] = \$this->request->getPost('val_".$value."') == null ? null : date('Y-m-d', strtotime(\$this->request->getPost('val_".$value."')));"; 
            } else {
                $fieldInserts .= "\n\t\t\$data['".$value."'] = \$this->request->getPost('val_".$value."');";
            }

            // jika required
            if (isset($fieldRequired[$key])) {
                $validationRequired .= "'val_".$value."' => 'required',";
            }

            // view detail
            $_value = $value;
            if (in_array($fieldType[$key], ['date'])) {
                $_value = 'DATE_FORMAT('.$value.', \'%d-%m-%Y\') as '.$value;
            } else if(in_array($fieldType[$key], ['image'])) {

            } else if(in_array($fieldType[$key], ['geometry'])) {
                $_value = 'ST_AsGeoJson('.$value.') as '.$value;
                $useFunction .= "use Shapefile\Shapefile;\nuse Shapefile\ShapefileException;\nuse Shapefile\ShapefileReader;";
            }
            if ($no == 1) {
                $viewDetail .= $primaryKey.', '.$_value;
            } else {
                $viewDetail .= ', '.$_value;
            }

            // if save empty
            if(in_array($fieldType[$key], ['geometry'])) {
                $ifGeometryEmpty = "\n\t\t\tif(!isset(\$data['".$value."'])){\n\t\t\t\t\$data['".$value."'] = new RawSql(\"ST_GeomFromText('POINT(0 0)')\");\n\t\t\t}";
            }
            $no++;
        }

    $controller = "@?php

namespace App\Controllers".$namespace.";

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models".$namespace.'\\'.$namaModel.";
".$useFunction."

class ".$namaController." extends BaseController
{
    private \$tema;

    function __construct()
    {
        helper(['form']);
        \$this->tema = new Tema();
    }

    public function index()
    {
        \$this->tema->setJudul('".$nama."');
        \$this->tema->loadTema('".$routeName."');
    }

    public function ajaxList()
    {
        \$".$modelVariable." = new ".$namaModel."();
        \$".$modelVariable."->setRequest(\$this->request);
        \$lists = \$".$modelVariable."->getDatatables();
        \$data = [];
        \$no = \$this->request->getPost(\"start\");
        foreach (\$lists as \$list) {
            \$no++;
            \$row = [];
            \$id = \$list->".$primaryKey.";
            \$aksi = '<a href=\"javascript:;\" class=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Lihat Data\" onclick=\"lihat_data('.\$id.')\"><i class=\"fa fa-search\"></i></a>';
            if(enforce(".$rbac.", 3)) {
                \$aksi .= '<a href=\"javascript:;\" class=\"ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Data\" onclick=\"edit_data('.\$id.')\"><i class=\"fa fa-edit\"></i></a>';
            }

            if(enforce(".$rbac.", 4)) {
                \$aksi .= '<a href=\"javascript:;\" class=\"text-danger ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Data\" onclick=\"delete_data('.\$id.')\"><i class=\"fa fa-trash\"></i></a>';
            }
            \$action = \$aksi;
            
            \$row[] = \$action;
            \$row[] = \$no;".$rowFields."
            \$data[] = \$row;
        }
        \$output = [
                \"draw\" => \$this->request->getPost('draw'),
                \"recordsTotal\" => \$".$modelVariable."->countAll(),
                \"recordsFiltered\" => \$".$modelVariable."->countFiltered(),
                \"data\" => \$data
            ];
        echo json_encode(\$output);
    }

    public function saveData()
    {
        \$".$modelVariable." = new ".$namaModel."();

        \$method = \$this->request->getPost('method');
        ".$fieldImg."

        \$validation = [
            ".$validationRequired."
        ];

        ".$formValidation."
        \$validated = \$this->validate(\$validation);
        if (\$validated === false) {
            \$errors = \$this->validator->getErrors();
            \$message = '<ul>';
            foreach (\$errors as \$key => \$value) {
                \$message .= '<li>'.\$value.'</li>';
            }
            ".$validationImg."
            \$message .= '</ul>';

            \$log['errorCode'] = 2;
            \$log['errorMessage'] = \$message;
            return \$this->response->setJSON(\$log);
        }

        \$id = \$this->request->getPost('".$primaryKey."');".$fieldInserts."

        if (\$method == 'save') {".$ifGeometryEmpty."
            \$".$modelVariable."->insert(\$data);
            \$log['errorCode'] = 1;
            \$log['errorMessage'] = 'Simpan Data Berhasil';
            \$log['errorType'] = 'success';
            return \$this->response->setJSON(\$log);
        } else {
            \$".$modelVariable."->update(\$id, \$data);
            \$log['errorCode'] = 1;
            \$log['errorMessage'] = 'Update Data Berhasil';
            \$log['errorType'] = 'success';
            return \$this->response->setJSON(\$log);
        }
    }

    public function getData(\$id)
    {
        \$".$modelVariable." = new ".$namaModel."();
        \$query = \$".$modelVariable."->select(\"".$viewDetail."\")->find(\$id);
        return \$this->response->setJSON(\$query);
    }

    public function deleteData(\$id)
    {
        \$".$modelVariable." = new ".$namaModel."();
        \$query = \$".$modelVariable."->delete(\$id);
        if (\$query) {
            \$log['errorCode'] = 1;
            \$log['errorMessage'] = 'Delete Data Berhasil';
            \$log['errorType'] = 'success';
            return \$this->response->setJSON(\$log);
        } else {
            \$log['errorCode'] = 2;
            \$log['errorMessage'] = 'Delete Data Gagal';
            \$log['errorType'] = 'warning';
            return \$this->response->setJSON(\$log);
        }
    }

    ".$functionExists."
}
";
    echo "<pre>".$controller."</pre>";

    // view

    $breadCumb = ucwords(str_replace('\\', '', $namespace));
    $explode = explode('/', $breadCumb);
    $viewBreadCumb = '';
    foreach ($explode as $key => $e) {
        if ($e != '') {
            $viewBreadCumb .= "<li class=\"breadcrumb-item\">".$e."</li>";
        }
    }
        $countfieldTable = count($viewTable);
        $width = 75/$countfieldTable;
        $tableTh = '';
        foreach ($viewTable as $key => $value) {
            $tableTh .= "\n\t\t\t\t\t\t\t\t\t".'<th style="width: '.$width.'%">'.$fieldAlias[$key].'</th>';
        }

        $formData = '';
        $attrLabel = '';
        $jsCustom = '';
        $jqFungsi = '';
        $jqReset = '';
        $jqReady = "\$(document).ready(function () {";
        $jsScript = '';
        $cssScript = '';
        $lihatDataJs = '';
        foreach ($fieldTable as $key => $value) {
            if (isset($fieldAttrLabel[$key]) && $fieldAttrLabel[$key] != null) {
                $attrLabel = ' ( '.$fieldAttrLabel[$key].' )';
            }

            if (in_array($fieldType[$key], ['text', 'number'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."');?@
                        @?=form_input('val_".$value."', '', ['class' => 'form-control'], '".$fieldType[$key]."');?@
                    </div>";
            }
            if (in_array($fieldType[$key], ['textarea'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."');?@
                        @?=form_textarea('val_".$value."', '', ['class' => 'form-control', 'rows' => 3]);?@
                    </div>";
            }
            if (in_array($fieldType[$key], ['koordinate'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."');?@
                        @?=form_input('val_".$value."', '', ['class' => 'form-control']);?@
                    </div>";
                    
                    $jsCustom .= "\n\t\t\$('[name=\"val_".$value."\"]').keyup(function(){\n\t\t\tvar str = $('[name=\"val_".$value."\"]').val();\n\t\t\t$('[name=\"val_".$value."\"]').val(str.replace(/[^\d.-]/g, \"\"));\n\t\t});";
            }
            if (in_array($fieldType[$key], ['rupiah'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."');?@
                        @?=form_input('val_".$value."', '', ['class' => 'form-control']);?@
                    </div>";
                $jsCustom .= "\n\t\t\$('[name=\"val_".$value."\"]').keyup(function(){\n\t\t\tvar str = $('[name=\"val_".$value."\"]').val();\n\t\t\t$('[name=\"val_".$value."\"]').val(formatRupiah(this.value, ''));\n\t\t});";
            }
            if (in_array($fieldType[$key], ['image'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\" id=\"divform_".$value."\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."', 'val_".$value."');?@
                        @?=form_upload('val_".$value."', '', ['class' => 'form-control', 'id' => 'val_".$value."', 'accept' => \".png,.jpg,.jpeg\", 'onchange' => \"readURL(this, 'img-preview-".$value."');\"]);?@
                    </div>
                    <div class=\"row\">
                        <div class=\"col-md-6\" id=\"divimage_".$value."\">
                            <h6 id=\"himage_".$value."\">".$fieldAlias[$key].$attrLabel."</h6>
                            <img src=\"@?=base_url('assets/admincast/dist/assets/img/image.jpg') ?@\" alt=\"\" class=\"img img-thumbnail img-preview \" id=\"img-preview-".$value."\" style=\"width: 100px; height: 100px;\">
                        </div>
                        <div class=\"col-md-6\" id=\"divcol_".$value."\">
                            <img src=\"@?=base_url('assets/admincast/dist/assets/img/image.jpg') ?@\" alt=\"\" class=\"img img-thumbnail img-preview\" id=\"img-old-".$value."\" style=\"width: 100px; height: 100px;\">
                        </div>
                    </div>";
                $jqReset .= "\$('#img-old-".$value."').attr('src', '@?=base_url('assets/admincast/dist/assets/img/image.jpg')?@');\n";
                $jqReset .= "\$('#divform_".$value."').show();\$('#divcol_".$value."').show();\$('#himage_".$value."').hide();";
                $lihatDataJs .= "\$('#divform_".$value."').hide();\$('#divcol_".$value."').hide();\$('#himage_".$value."').show();";
            }
            if (in_array($fieldType[$key], ['geometry'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].' ( shp )'."');?@
                        @?=form_upload('val_".$value."', '', ['class' => 'form-control', 'accept' => '.shp', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?@
                    </div>\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].' ( shx )'."');?@
                        @?=form_upload('val_".$value."_shx', '', ['class' => 'form-control', 'accept' => '.shx', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?@
                    </div>\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].' ( dbf )'."');?@
                        @?=form_upload('val_".$value."_dbf', '', ['class' => 'form-control', 'accept' => '.dbf', 'onchange' => 'shpRequired()', 'onkeyup' => 'shpRequired()']);?@
                    </div>\n\t\t\t\t\t<div id=\"map_".$value."\"></div>";
                $jqFungsi .= "\n\tfunction shpRequired() {\n\t\t$('[name=\"val_".$value."\"]').attr('required', 'true');\n\t\t$('[name=\"val_".$value."_shx\"]').attr('required', 'true');\n\t\t$('[name=\"val_".$value."_dbf\"]').attr('required', 'true');\n\t};";
                $jqFungsi .= "\n\n\tfunction resetShpRequired() {\n\t\t$('[name=\"val_".$value."\"]').removeAttr('required');\n\t\t$('[name=\"val_".$value."_shx\"]').removeAttr('required');\n\t\t$('[name=\"val_".$value."_dbf\"]').removeAttr('required');\n\t};";
                $jqReset .= 'resetShpRequired();';
                $cssScript = "<style>
                #map_".$value." {
                    width: 100%;
                    height: 40vh;
                }
            </style>";
                $jsScript = "<script src=\"https://unpkg.com/leaflet@1.8.0/dist/leaflet.js\"
                integrity=\"sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==\"
                crossorigin=\"\"></script>
        
        <script src=\"https://maps.google.com/maps/api/js?v=3.2&key=AIzaSyBy8irzccEOp7ezf5nQ2UPaLGcte_AEsOQ\"></script>
        <script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js'></script>
        
        <script>
            var no = 1;
            var layers = new Array();
            
            var map = L.map('map_".$value."').setView(['-6.588706', '110.775882'], 12);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
        
            var osm = L.tileLayer(\"http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\");
        
            var google_satelit = L.gridLayer.googleMutant({
                type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid' 
            })
        
            var baseMaps = {
                \"OpenStreetMap\": osm,
                \"Google Satellite\": google_satelit,
            };
        
            var overlays = { //add any overlays here
        
            };
        
            L.control.layers(baseMaps, overlays, {
                position: 'bottomleft'
            }).addTo(map);
        
            // var tabMap = document.getElementById('tab-map');
            // var observer1 = new MutationObserver(function(){
            //     if(tabMap.style.display != 'none'){
            //         map.invalidateSize();
            //     }
            // });
            // observer1.observe(tabMap, {attributes: true}); 
        </script>";
            }
            if (in_array($fieldType[$key], ['date'])) {
                $formData .= "\n\t\t\t\t\t<div class=\"form-group\">
                        @?=form_label('".$fieldAlias[$key].$attrLabel."');?@
                        @?=form_input('val_".$value."', '', ['class' => 'form-control']);?@
                    </div>";
                $jqFungsi .= "\n\n\t$('[name=\"val_".$value."\"]').datepicker({\n\t\ttodayBtn: \"linked\",\n\t\tkeyboardNavigation: false,\n\t\tforceParse: false,\n\t\tcalendarWeeks: true,\n\t\tautoclose: true,\n\t\tformat: \"dd-mm-yyyy\"\n\t});";
            }
        }
        $jqReady .= $jsCustom;
        $jqReady .= "\n\t});";

        $editData = '';
        foreach ($fieldTable as $key => $value) {
            if (in_array($fieldType[$key], ['rupiah'])) {
                $editData .= "\$('[name=\"val_".$value."\"]').val(formatRupiah(response.".$value."));\n\t\t\t\t";
            } else if (in_array($fieldType[$key], ['image'])) {
                $editData .= "\$('#img-old-".$value."').attr('src', '@?=base_url('')?@/'+response.".$value.");\n\t\t\t\t";
                $editData .= "\$('#img-preview-".$value."').attr('src', '@?=base_url('assets/admincast/dist/assets/img/image.jpg')?@');\n\t\t\t\t";
            } else if (in_array($fieldType[$key], ['geometry'])) {

            } else {
                $editData .= "\$('[name=\"val_".$value."\"]').val(response.".$value.");\n\t\t\t\t";
            }
        }

        $formDataRules = '';
        $formDataMessages = "";
        foreach ($fieldTable as $key => $value) {            
            $remote = '';
            $remoteMessage = '';
            if (isset($fieldTableRemote[$key])) {
            $remote .= "\n\t\t\t\tremote: {
                    url: \"@?=base_url('".$routeName."/".$value."_exist'); ?@\",
                    type: \"post\",
                    data: {
                        ".$value.": function() {
                            return \$('[name=\"val_".$value."\"]').val();
                        },
                        ".$primaryKey.": function() {
                            return \$('[name=\"".$primaryKey."\"]').val();
                        },
                        csrf_test_name: function() {
                            return \$('meta[name=X-CSRF-TOKEN]').attr(\"content\");
                        },
                    },
                },
";

$remoteMessage = "remote: '".$fieldAlias[$key]." sudah Ada, Tidak bisa di Input',";
            }
$formMaxLength = '';
$formMaxLengthMessages = '';
if (isset($maxLength[$key]) && $maxLength[$key] != null) {
    $formMaxLength .= "\n\t\t\t\tmaxlength: ".$maxLength[$key];
    $formMaxLengthMessages .= "maxlength: '".$fieldAlias[$key]." Tidak Boleh Lebih dari ".$maxLength[$key]." Huruf'";
}

$required = '';
$messageRequired = '';
if (isset($fieldRequired[$key])) {
    $required = 'required: true,';
    $messageRequired = "required:'".$fieldAlias[$key]." harus diisi',";
}
$formDataRules .= "\n\t\t\tval_".$value.": {
                ".$required.$remote.$formMaxLength."
            },
";

$formDataMessages .= "\n\t\t\t\tval_".$value.": {
                    ".$messageRequired.$remoteMessage.$formMaxLengthMessages."
                },
";
        }

$view = "
@?= \$this->extend('tema/tema'); ?@ 
@?=\$this->section('css');?@
<!-- Data Table CSS -->
<link href=\"@?=base_url();?@/assets/alertifyjs/css/alertify.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/alertifyjs/css/themes/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css\" rel=\"stylesheet\" type=\"text/css\" />
".$cssScript."
@?=\$this->endSection();?@

@?=\$this->section('content'); ?@
<div class=\"page-heading\">
    <h1 class=\"page-title\">".ucwords($nama)."</h1>
    <ol class=\"breadcrumb\">
        <li class=\"breadcrumb-item\">
            <a href=\"@?=base_url('home');?@\"><i class=\"fa fa-home font-20\"></i></a>
        </li>
        ".$viewBreadCumb."
        <li class=\"breadcrumb-item\">".ucwords($nama)."</li>
    </ol>
</div>

<!-- Container -->
<div class=\"page-content fade-in-up\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            <div class=\"ibox\">
                <div class=\"ibox-head\">
                    <div class=\"ibox-title\">Data ".$nama."</div>
                    <div class=\"ibox-tools\">
                        <a onclick=\"reload_table()\" class=\"refresh\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"reload data\"><i class=\"fa fa-refresh\"></i></a>
                        @?php if(enforce(".$rbac.", 2)): ?@
                            <a class=\"\" onclick=\"tambah_data()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"tambah data\"><i class=\"fa fa-plus-square\"></i></a>
                        @?php endif ?@
                    </div>
                </div>
                <div class=\"ibox-body\">
                    <div class=\"table-responsive\">
                        <table id=\"datatable\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th width=\"15%\">Action</th>
                                    <th width=\"10%\">No</th>".$tableTh."
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

<div class=\"modal fade\" id=\"modal".$url."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
    <div class=\"modal-dialog\" role=\"document\">
        @?=form_open('', ['id' => 'form".$url."'], ['".$primaryKey."' => '', 'method' => '']);?@
            @?=csrf_field();?@
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h5 class=\"modal-title\">Modal title</h5>
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>
                </div>
                <div class=\"modal-body\">".$formData."
                </div>
                <div class=\"modal-footer\">
                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
                    <button type=\"submit\" class=\"btn btn-primary\"><i class=\"fa fa-save\"></i> Simpan</button>
                </div>
            </div>
        @?=form_close();?@
    </div>
</div>
@?=\$this->endSection();?@
@?=\$this->section('js');?@
<script src=\"@?=base_url(); ?@/assets/alertifyjs/alertify.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/moment/min/moment.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"> </script>
".$jsScript."
<script>
    var table;
    var save_method;
    \$(document).ready(function () {
        table = \$('#datatable').DataTable({
                    scrollCollapse: true,
                    responsive: true,
                    autoWidth: false,
                    language: { search: \"\",
                        searchPlaceholder: \"Search\",
                        sLengthMenu: \"_MENU_items\"
                    },
                    \"order\": [],
                    \"ajax\": {
                        \"url\": \"@?php echo base_url('".$routeName."/ajax_list') ?@\",
                        \"headers\": {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        \"type\": \"POST\",
                        \"data\": {@?=csrf_token();?@: '@?=csrf_hash()?@'},
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    },
                    //optional
                    \"columnDefs\": [{
                        \"targets\": [0, 1],
                        \"orderable\": false,
                    }, ],
                });
    });
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    ".$jqReady."
    ".$jqFungsi."

    function reset_form() {
        var MValid = \$(\"#form".$url."\");
        MValid[0].reset();
        MValid.find(\".is-invalid\").removeClass(\"is-invalid\");
        MValid.find(\".is-valid\").removeClass(\"is-valid\");
        ".$jqReset."
    }

    function lihat_data(id) {
        reset_form();
        save_method = 'update';
        \$('#form".$url."').valid();
        \$('[name=\"method\"]').val('update');
        \$('#form".$url." .form-control').addClass('form-view-detail');
        \$('#form".$url." .form-control').prop('disabled', true);
        \$('#form".$url." button[type=\"submit\"]').hide();
        \$.ajax({
            type: \"GET\",
            url: \"@?=base_url('".$routeName."');?@/\"+id+'/get_data',
            dataType: \"JSON\",
            success: function (response) {
                \$('#modal".$url."').modal('show');
                \$('#modal".$url." .modal-title').text('Detail Data');
                \$('[name=\"".$primaryKey."\"]').val(response.".$primaryKey.");
                ".$lihatDataJs."".$editData."
            }
        });
    }

    @?php if(enforce(".$rbac.", 2)): ?@
    function tambah_data() {
        save_method = 'save';
        reset_form();
        \$('#modal".$url."').modal('show');
        \$('#modal".$url." .modal-title').text('Tambah Data');
        \$('[name=\"method\"]').val('save');
        \$('[name=\"".$primaryKey."\"]').val(null);
        \$('#form".$url." .form-control').removeClass('form-view-detail');
        \$('#form".$url." .form-control').prop('disabled', false);
        \$('#form".$url." button[type=\"submit\"]').show();
    }
    @?php endif ?@

    @?php if(enforce(".$rbac.", 3)): ?@
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        \$('#form".$url."').valid();
        \$('[name=\"method\"]').val('update');
        \$('#form".$url." .form-control').removeClass('form-view-detail');
        \$('#form".$url." .form-control').prop('disabled', false);
        \$('#form".$url." button[type=\"submit\"]').show();
        \$.ajax({
            type: \"GET\",
            url: \"@?=base_url('".$routeName."');?@/\"+id+'/get_data',
            dataType: \"JSON\",
            success: function (response) {
                \$('#modal".$url."').modal('show');
                \$('#modal".$url." .modal-title').text('Edit Data');
                \$('[name=\"".$primaryKey."\"]').val(response.".$primaryKey.");
                ".$editData."
            }
        });
    }
    @?php endif ?@

    @?php if(enforce(".$rbac.", 4)): ?@
    function delete_data(id) {
        Swal.fire({
        title: 'Apa Anda Yakin?',
        text: \"Anda Tidak Dapat Mengembalikan Data Yang Telah Di Hapus\",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Hapus !'
        }).then((result) => {
        if (result.isConfirmed) {
            \$.ajax({
                type: \"DELETE\",
                url: \"@?=base_url('".$routeName."')?@/\"+id+'/delete_data',
                dataType: \"json\",
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
    @?php endif ?@

    \$(function() {
        \$('#form".$url."').validate({
            errorClass: \"invalid-feedback\",
            rules: {".$formDataRules."
            },
            messages: {".$formDataMessages."

            },
            highlight: function(e) {
                \$(e).closest(\".form-control\").removeClass(\"is-valid\").addClass(\"is-invalid\");
            },
            unhighlight: function(e) {
                \$(e).closest(\".form-control\").removeClass(\"is-invalid\").addClass(\"is-valid\");
            },
            submitHandler: function() {
                var url = '';
                if(save_method == 'update') {
                    url = \"@?=base_url('".$routeName."/update_data');?@\";
                }
                if(save_method == 'save') {
                    url = \"@?=base_url('".$routeName."/save_data');?@\";
                }
                var formData = new FormData($($('#form".$url."'))[0]);
                \$.ajax({
                    type: \"POST\",
                    url: url,
                    data: formData,
                    dataType: \"JSON\",
                    processData: false,
                    contentType:false,
                    cache : false,
                    success: function (response) {
                        if(response.errorCode == 1) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class=\"fa fa-bell\"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                            reload_table();
                            \$('#modal".$url."').modal('hide');
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
@?=\$this->endSection();?@
";
    echo "<pre>".$view."</pre>";

    // created Controller
    if (!file_exists(ROOTPATH.'App\Controllers'.$namespace)) {
        mkdir(ROOTPATH.'App\Controllers'.$namespace, 7775);
    }
    $pathController = ROOTPATH.'App\Controllers'.$namespace.'\\'.$namaController.'.php';
    $controller = str_replace('@?', '<?', $controller);
    // if (!file_exists($pathController)) {
    //     self::createFile($controller, $pathController);
    // } else {
    //     echo "File Sudah Ada";
    // }
    self::createFile($controller, $pathController);
    

    if (!file_exists(ROOTPATH.'App\Models'.$namespace)) {
        mkdir(ROOTPATH.'App\Models'.$namespace, 775);
    }
    $pathModel = ROOTPATH.'App\Models'.$namespace.'\\'.$namaModel.'.php';
    $model = str_replace('@?', '<?', $model);
    // if (!file_exists($pathModel)) {
    //     self::createFile($model, $pathModel);
    // } else {
    //     echo "File Sudah Ada";
    // }
    self::createFile($model, $pathModel);

    if (!file_exists(ROOTPATH.'App\Views'.strtolower($namespace))) {
        mkdir(ROOTPATH.'App\Views'.strtolower($namespace), 775);
    }
    $pathView = ROOTPATH.'App\Views'.strtolower($namespace)."\\".str_replace(' ', '', strtolower($nama)).".php";
    $view = str_replace('@?', '<?', $view);
    $view = str_replace('?@', '?>', $view);
    // if (!file_exists($pathView)) {
    //     self::createFile($view, $pathView);
    // } else {
    //     echo "File Sudah Ada";
    // }
    self::createFile($view, $pathView);
    }

    function createFile($string, $path)
    {
        $create = fopen($path, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $string);
        fclose($create);
        
        return $path;
    }
}
