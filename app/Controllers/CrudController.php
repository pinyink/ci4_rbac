<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;

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

        $tema = new Tema();
        $tema->loadTema('crud/viewTableStrukture', ['table' => $table, 'fields' => $fields]);
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
        $rbac = $request->getPost('rbac');

        $fieldTable = $request->getPost('fieldTable');
        $fieldAlias = $request->getPost('fieldAlias');
        $fieldTableRemote = $request->getPost('fieldTableRemote');
        $maxLength = $request->getPost('maxLength');

        $namaController = str_replace(' ', '', ucwords(strtolower($nama))).'Controller';
        $url = str_replace(' ', '', strtolower($nama));

        $routeName = strtolower(str_replace('\\', '/', $namespace)).'/'.$url;
        $routeExistField = "";
        foreach ($fieldTable as $key => $value) {
            if (isset($fieldTableRemote[$key])) {
                $routeExistField .= "\n\t#routes->post('".$value."_exist', '".$namaController."::".strtolower(str_replace('_', '', $value))."Exist', ['filter' => 'auth:N,".$rbac.",2']);";
            }
        }
$route = "
#routes->group('".$routeName."', ['namespace' => 'App\Controllers".$namespace."'], static function(#routes) {
    #routes->get('/', '".$namaController."::index', ['filter' => 'auth:Y,".$rbac.",1']);
    #routes->post('ajax_list', '".$namaController."::ajaxList', ['filter' => 'auth:N,".$rbac.",1']);
    #routes->post('save_data', '".$namaController."::saveData', ['filter' => 'auth:N,".$rbac.",2']);
    #routes->get('get_data/(:num)', '".$namaController."::getData/$1', ['filter' => 'auth:N,".$rbac.",2']);
    #routes->delete('delete_data/(:num)', '".$namaController."::deleteData/$1', ['filter' => 'auth:N,".$rbac.",3']);".$routeExistField."
});";

$namaModel = str_replace(' ', '', ucwords(strtolower($nama))).'Model';
$columnSearch = '';
$allowedFields = '';
$selectFields = '';
$no = 1;
foreach ($fieldTable as $key => $value) {
    if ($no == 1) {
        $columnSearch .= "'a.".$value."'";
        $allowedFields .= "'".$value."'";
        $selectFields .= "a.".$value."";
    } else {
        $columnSearch .= ", 'a.".$value."'";
        $allowedFields .= ", '".$value."'";
        $selectFields .= ", a.".$value."";
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
    public \$order         = array('a.".$orderBy."' => 'asc');

    private \$request = '';
    private \$dt;
    private \$where = [];
    
    public function setWhere(\$where = [])
    {
        \$this->where = \$where;

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

    $view = "
@?= \$this->extend('default'); ?@ 
@?=\$this->section('judul');?@
".$nama."
@?=\$this->endSection();?@
@?=\$this->section('css');?@
<!-- Data Table CSS -->
<link href=\"@?=base_url();?@/vendors/datatables.net-dt/css/jquery.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\" />
@?=\$this->endSection();?@
@?=\$this->section('breadcrumb');?@
<li class=\"breadcrumb-item\"><a href=\"javascript:;\">".$breadCumb."</a></li>
<li class=\"breadcrumb-item active\"><a href=\"javascript:;\">".ucwords($nama)."</a></li>
@?=\$this->endSection();?@
@?=\$this->section('content'); ?@
<!-- Container -->
<div class=\"container-fluid\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            <div class=\"hk-row\">
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                    <div class=\"card card-refresh\" id=\"card".$url."\">
                        <div class=\"refresh-container\">
                            <div class=\"loader-pendulums\"></div>
                        </div>
                        <div class=\"card-header card-header-action\">
                            <div>
                                <h5 class=\"hk-sec-title\">Data ".$nama."</h5>
                            </div>
                            <div class=\"card-action-wrap\">
                                <a onclick=\"reload_table()\" class=\"refresh\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"reload data\"><i class=\"ion ion-md-refresh\"></i></a>
                                <a class=\"\" onclick=\"tambah_data()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"tambah data\"><i class=\"ion ion-md-add\"></i></a>
                            </div>
                        </div>
                        <div class=\"card-body\">
                            <div class=\"\">
                                <div class=\"table-wrap table-responsive\">
                                    <table id=\"datable_1\" class=\"table table-striped w-100 display pb-30\">
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
                    <button type=\"button\" class=\"btn btn-sm btn-secondary\" data-dismiss=\"modal\">Close</button>
                    <button type=\"submit\" class=\"btn btn-sm btn-primary\">Save changes</button>
                </div>
            </div>
        @?=form_close();?@
    </div>
</div>
@?=\$this->endSection();?@
@?=\$this->section('js');?@
<script src=\"@?=base_url(); ?@/vendors/jquery-validation/dist/jquery.validate.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url();?@/vendors/datatables.net/js/jquery.dataTables.min.js\"> </script>
<script src=\"@?=base_url();?@/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js\"> </script>
<script src=\"@?=base_url();?@/vendors/datatables.net-dt/js/dataTables.dataTables.min.js\"> </script>
<script>
    var data_table = data_csrf;
    var table;
    var save_method;
    \$(document).ready(function () {
        table = \$('#datable_1').DataTable({
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
                        \"data\": data_table,
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

    function reset_form() {
        var MValid = \$(\"#form".$url."\");
        MValid[0].reset();
        MValid.find(\".is-invalid\").removeClass(\"is-invalid\");
        MValid.find(\".is-valid\").removeClass(\"is-valid\");
    }

    function tambah_data() {
        save_method = 'save';
        reset_form();
        \$('#modal".$url."').modal('show');
        \$('#modal".$url." .modal-title').text('Tambah Data');
        \$('[name=\"method\"]').val('save');
        \$('[name=\"".$primaryKey."\"]').val(null);
    }

    function edit_data(id) {
        reset_form();
        \$('#form".$url."').valid();
        \$('[name=\"method\"]').val('update');
        \$.ajax({
            type: \"GET\",
            url: \"@?=base_url('".$routeName."/get_data');?@/\"+id,
            dataType: \"JSON\",
            success: function (response) {
                \$('#modal".$url."').modal('show');
                \$('#modal".$url." .modal-title').text('Edit Data');
                \$('[name=\"".$primaryKey."\"]').val(response.".$primaryKey.");
                ".$editData."
            }
        });
    }

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
                url: \"@?=base_url('".$routeName."/delete_data')?@/\"+id,
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
                startLoading('card".$url."');
                var url = \"@?=base_url('".$routeName."/save_data');?@\";
                \$.ajax({
                    type: \"POST\",
                    url: url,
                    data: \$('#form".$url."').serialize(),
                    dataType: \"JSON\",
                    success: function (response) {
                        if(response.errorCode == 1) {
                            toast_success(response.errorMessage);
                            reload_table();
                            \$('#modal".$url."').modal('hide');
                        } else {
                            toast_error(response.errorMessage);
                        }
                        stopLoading('card".$url."');
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
    }
}
