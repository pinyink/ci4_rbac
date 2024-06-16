<?php

namespace App\Libraries;

class CreateControllerLib
{
    private $table;
    private $fields;

    /**
     * Get the value of table
     */ 
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the value of table
     *
     * @return  self
     */ 
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the value of fields
     */ 
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @return  self
     */ 
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function generate()
    {
        $namaController = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table['table']))).'Controller';
        $modelVariable = str_replace('_', ' ', $this->table['table']).'Model';
        $namaModel = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table['table']))).'Model';

$controller = "@?php

namespace App\Controllers".$this->table['namespace'].";

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\\".$namaModel.";

class ".$namaController." extends BaseController
{
    private \$tema;
    private \$".$modelVariable.";

    function __construct()
    {
        helper(['form', 'Permission_helper', 'FormCustom']);
        \$this->tema = new Tema();
        \$this->".$modelVariable." = new ".$namaModel."();
    }

    public function index()
    {
        \$this->tema->setJudul('".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/index');
    }";

        $rowFields = "";
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                if ($value['name_type'] == 'text' || $value['name_type'] == 'textarea') {
                    $rowFields .= "\n\t\t\t\$row[] = \$list->".$value['name_field'].";";
                }
            }
        }
    $controller .= "\n\n\tpublic function ajaxList()
    {
        \$this->".$modelVariable."->setRequest(\$this->request);
        \$lists = \$this->".$modelVariable."->getDatatables();
        \$data = [];
        \$no = \$this->request->getPost(\"start\");
        foreach (\$lists as \$list) {
            \$no++;
            \$row = [];
            \$id = \$list->".$this->table['primary_key'].";
            \$aksi = '<a href=\"javascript:;\" class=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Lihat Data\" onclick=\"lihat_data('.\$id.')\"><i class=\"fa fa-search\"></i></a>';
            if(enforce(".$this->table['rbac'].", 3)) {
                \$aksi .= '<a href=\"javascript:;\" class=\"ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Data\" onclick=\"edit_data('.\$id.')\"><i class=\"fa fa-edit\"></i></a>';
            }

            if(enforce(".$this->table['rbac'].", 4)) {
                \$aksi .= '<a href=\"javascript:;\" class=\"text-danger ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Data\" onclick=\"delete_data('.\$id.')\"><i class=\"fa fa-trash\"></i></a>';
            }
            \$action = \$aksi;
            
            \$row[] = \$action;
            \$row[] = \$no;".$rowFields."
            \$data[] = \$row;
        }
        \$output = [
                \"draw\" => \$this->request->getPost('draw'),
                \"recordsTotal\" => \$this->".$modelVariable."->countAll(),
                \"recordsFiltered\" => \$this->".$modelVariable."->countFiltered(),
                \"data\" => \$data
            ];
        echo json_encode(\$output);
    }";
        // set validation rules
    $rules = "";
    foreach ($this->fields as $key => $value) {
        $rule = [];
        $errors = [];

        if ($value['field_required'] == 1 ) {
            array_push($rule, 'required');
            array_push($errors, trim("'required' => '{field} Harus di isi'"));
        }
        if ($value['field_min'] > 0) {
            array_push($rule, 'min_length['.$value['field_min'].']');
            array_push($errors, "'min_length' => '{field} Harus Lebih Dari ".$value['field_min']." Huruf'");
        }
        if ($value['field_max'] > 0) {
            array_push($rule, 'max_length['.$value['field_max'].']');
            array_push($errors, "'max_length' => '{field} Maksimal ".$value['field_max']." Huruf'");
        }
        $errors = implode(",\n\t\t\t\t\t", $errors);
        $rules .= "\n\t\t\t'".$value['name_field']."' => [
                'label' => '".$value['name_alias']."',
                'rules' => '".implode('|', $rule)."',
                'errors' => [
                    ".$errors."
                ]
            ],";
    }
    $controller .= "\n\n\tpublic function rules(\$id = null)
    {
        \$rules = [".$rules."
        ];

        return \$rules;
    }";

    $controller .= "\n\n\tpublic function tambahData(){
        \$data = [
            'button' => 'Simpan',
            'id' => '',
            'method' => 'save',
            'url' => '".$this->table['table']."/save_data'
        ];
        \$this->tema->setJudul('Tambah ".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/tambah', \$data);
    }";

    $controller .= "\n\n\tpublic function saveData(\$id = null)
    {
        \$validation = service('validation');
        \$request    = service('request');
        \$validation->setRules(\$this->rules());

        if (\$validation->withRequest(\$request)->run()) {
            \$validData = \$validation->getValidated();
        } else {
            \$error = \$validation->getErrors();
        }
        \$method = \$request->getPost('method');
        if(\$method == 'save') {
            return redirect()->to('".$this->table['table']."/tambah')->withInput();
        } else {
            return redirect()->to('".$this->table['table']."/edit')->withInput();
        }
    }";

$controller .= "\n}";

        if (!file_exists(ROOTPATH.'app/Controllers')) {
            mkdir(ROOTPATH.'app/Controllers', 775);
        }
        $pathController = ROOTPATH.'app/Controllers/'.$namaController.'.php';
        $controller = str_replace('@?', '<?', $controller);
        $create = fopen($pathController, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $controller);
        fclose($create);
    }
}