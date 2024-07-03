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

        $rowFields = [];
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                if ($value['name_type'] == 'text' || $value['name_type'] == 'textarea') {
                    array_push($rowFields, "\$row[] = \$list->".$value['name_field'].";");
                }
                if ($value['name_type'] == 'number') {
                    array_push($rowFields, "\$row[] = number_format(\$list->".$value['name_field'].", 0, ',', '.');");
                }
                if ($value['name_type'] == 'rupiah') {
                    array_push($rowFields, "\$row[] = 'Rp. '.number_format(\$list->".$value['name_field'].", 0, ',', '.');");
                }
                if ($value['name_type'] == 'date') {
                    array_push($rowFields, "\$row[] = date('d-m-Y', strtotime(\$list->".$value['name_field']."));");
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
            \$aksi = '<a href=\"'.base_url('".$this->table['table']."/'.\$id.'/detail').'\" class=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Lihat Data\"><i class=\"fa fa-search\"></i></a>';
            if(enforce(".$this->table['rbac'].", 3)) {
                \$aksi .= '<a href=\"'.base_url('".$this->table['table']."/'.\$id.'/edit').'\" class=\"ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Data\" onclick=\"edit_data('.\$id.')\"><i class=\"fa fa-edit\"></i></a>';
            }

            if(enforce(".$this->table['rbac'].", 4)) {
                \$aksi .= '<a href=\"javascript:;\" class=\"text-danger ml-2\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Data\" onclick=\"delete_data('.\$id.')\"><i class=\"fa fa-trash\"></i></a>';
            }
            \$action = \$aksi;
            
            \$row[] = \$action;
            \$row[] = \$no;
            ".implode("\n\t\t\t", $rowFields)."
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
            array_push($errors, "'required' => '{field} Harus di isi'");
        }
        if($value['field_unique'] == 1) {
            array_push($rule, 'is_unique['.$this->table['table'].'.'.$value['name_field'].', '.$this->table['primary_key'].', \'.$id.\']');
            array_push($errors, "'is_unique' => '{field} Sudah Ada, harap ketik yang lainnya'");
        }
        if ($value['field_min'] > 0) {
            array_push($rule, 'min_length['.$value['field_min'].']');
            array_push($errors, "'min_length' => '{field} Harus Lebih Dari ".$value['field_min']." Huruf'");
        }
        if ($value['field_max'] > 0) {
            array_push($rule, 'max_length['.$value['field_max'].']');
            array_push($errors, "'max_length' => '{field} Maksimal ".$value['field_max']." Huruf'");
        }
        // jika text
        if ($value['name_type'] == 'text') {
            array_push($rule, 'alpha_numeric_space');
            array_push($errors, "'alpha_numeric_space' => '{field} Hanya berupa huruf, angka dan spasi'");
        }

        // jika textarea
        if ($value['name_type'] == 'textarea') {
            array_push($rule, 'alpha_numeric_punct');
            array_push($errors, "'alpha_numeric_punct' => '{field} Hanya berupa huruf, angka dan karakter tertentu'");
        }
        // jika number
        if ($value['name_type'] == 'number' || $value['name_type'] == 'rupiah') {
            array_push($rule, 'numeric');
            array_push($errors, "'numeric' => '{field} Hanya berupa angka'");
        }
        // jika tanggal / date
        if ($value['name_type'] == 'date') {
            array_push($rule, 'valid_date[d-m-Y]');
            array_push($errors, "'valid_date' => '{field} Harus berupa tanggal dd-mm-yyyy'");
        }

        if (!in_array($value['name_type'], ['image'])) {
            $errors = implode(",\n\t\t\t\t\t", $errors);
            $rules .= "\n\t\t\t'".$value['name_field']."' => [
                'label' => '".$value['name_alias']."',
                'rules' => '".implode('|', $rule)."',
                'errors' => [
                    ".$errors."
                ]
            ],";
        }
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
            'url' => '".$this->table['table']."/save'
        ];
        \$this->tema->setJudul('Tambah ".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/tambah', \$data);
    }";

    $controller .= "\n\n\tpublic function editData(\$id){
        \$query = \$this->".$modelVariable."->detail(['a.".$this->table['primary_key']."' => \$id])->getRowArray();
        if(empty(\$query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        \$data = [
            'button' => 'Simpan',
            'id' => \$id,
            'method' => 'update',
            'url' => '".$this->table['table']."/update',
            '".$this->table['table']."' => \$query
        ];
        \$this->tema->setJudul('Edit ".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/edit', \$data);
    }";

    $optValidation = [];
    foreach ($this->fields as $key => $value) {
        if ($value['name_type'] == 'image') {
            $v = "if (!empty(\$_FILES['".$value['name_field']."']['name'])) {
            \$rules['".$value['name_field']."'] = [
                'label' => '".$value['name_alias']."',
                'rules' => 'uploaded[".$value['name_field']."]|is_image[".$value['name_field']."]|mime_in[".$value['name_field'].",image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[".$value['name_field'].",2048]',
                'errors' => [
                    'max_size' => '{field} maksimal 2mb',
                    'mime_in' => '{field} hanya upload file png, jpeg, jpg',
                    'uploaded' => '{field} Tidak Sesuai',
                    'is_image' => '{field} hanya upload file png, jpeg, jpg'
                ]
            ];
        }";
            array_push($optValidation, $v);
        }
    }

    $requestData = [];
    foreach ($this->fields as $key => $value) {
        // jika number / rupiah
        if ($value['name_type'] == 'number' || $value['name_type'] == 'rupiah') {
            array_push($requestData, "\$validData['".$value['name_field']."'] = str_replace('.', '', \$validData['".$value['name_field']."']);");
        }
        // jika date
        if ($value['name_type'] == 'date') {
            array_push($requestData, "\$validData['".$value['name_field']."'] = date('Y-m-d', strtotime(\$validData['".$value['name_field']."']));");
        }

        // jika image
        if ($value['name_type'] == 'image') {
            $v = "\$".$value['name_field']." = \$request->getFile('".$value['name_field']."');
            if (!empty(\$_FILES['".$value['name_field']."']['name'])) {
                \$th = date('Y/m/d');
                \$path = 'uploads/".$this->table['table']."/';
                \$_dir = \$path . \$th;
                \$dir = UPLOADPATH . \$path . \$th;
                if (!file_exists(\$dir)) {
                    mkdir(\$dir, 0777, true);
                }
                \$newName = \$".$value['name_field']."->getRandomName();;
                \$".$value['name_field']."->move(\$dir, \$newName);
                \$validData['".$value['name_field']."'] = \$_dir.'/'.\$newName;
            }";

            array_push($requestData, $v);
        }
    }
    $controller .= "\n\n\tpublic function saveData(\$id = null)
    {
        \$validation = service('validation');
        \$request    = service('request');
        //get method form
        \$id = \$request->getPost('id');
        \$method = \$request->getPost('method');
        //set rules validation
        \$rules = \$this->rules(\$id);
        ".implode("\n\t\t", $optValidation)."
        \$validation->setRules(\$rules);

        if (\$validation->withRequest(\$request)->run()) {
            \$validData = \$validation->getValidated();
            ".implode("\n\t\t\t", $requestData)."
            if(\$method == 'save') {
                \$id = \$this->".$modelVariable."->insert(\$validData);
                return redirect()->to('".$this->table['table']."/'.\$id.'/detail')->with('message', '<div class=\"alert alert-success\">Simpan Data Berhasil</div>');
            } else {
                \$this->".$modelVariable."->update(\$id, \$validData);
                return redirect()->to('".$this->table['table']."/'.\$id.'/edit')->with('message', '<div class=\"alert alert-success\">Update Data Berhasil</div>');
            }
        } else {
            if(\$method == 'save') {
                return redirect()->to('".$this->table['table']."/tambah')->withInput();
            } else {
                return redirect()->to('".$this->table['table']."/'.\$id.'/edit')->withInput();
            }
        }
        
    }";

    $controller .= "\n\n\tpublic function detailData(\$id){
        \$query = \$this->".$modelVariable."->detail(['a.".$this->table['primary_key']."' => \$id])->getRowArray();
        if(empty(\$query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        \$data = [
            '".$this->table['table']."' => \$query
        ];
        \$this->tema->setJudul('Detail ".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/detail', \$data);
    }";

    $controller .= "\n\n\tpublic function deleteData(\$id){
        \$query = \$this->".$modelVariable."->find(\$id);
        if(empty(\$query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        \$data = [
            '".$this->table['table']."' => \$query
        ];
        \$delete = \$this->".$modelVariable."->delete(\$id);
        if(\$delete) {
            \$log['errorCode'] = 1;
        } else {
            \$log['errorCode'] = 2;
        }
        return \$this->response->setJSON(\$log);
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