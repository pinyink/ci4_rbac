<?php

namespace App\Libraries;

class CreateControllerLib
{
    private $table;
    private $fields;
    private $modelLib;

    public function __construct() {
        $this->modelLib = new CreateModelLib();
    }

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
        helper(['form', 'Permission_helper']);
        \$this->tema = new Tema();
        \$this->".$modelVariable." = new ".$namaModel."();
    }

    public function index()
    {
        \$this->tema->setJudul('".$this->table['title']."');
        \$this->tema->loadTema('".$this->table['routename']."/index');
    }";

$controller .= "\n}";

        if (!file_exists(ROOTPATH.'App\Models')) {
            mkdir(ROOTPATH.'App\Models', 775);
        }
        $pathController = ROOTPATH.'App\Controllers\\'.$namaController.'.php';
        $controller = str_replace('@?', '<?', $controller);
        $create = fopen($pathController, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $controller);
        fclose($create);
    }
}