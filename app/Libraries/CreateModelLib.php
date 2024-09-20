<?php

namespace App\Libraries;

class CreateModelLib 
{
    private $table;
    private $fields;
    private $namaModel;

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

    /**
     * Get the value of namaModel
     */ 
    public function getNamaModel()
    {
        $this->namaModel = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table['table']))).'Model';
        return $this->namaModel;
    }

    /**
     * Set the value of namaModel
     *
     * @return  self
     */ 
    public function setNamaModel($namaModel)
    {
        $this->namaModel = $namaModel;

        return $this;
    }

    public function generate()
    {
        $namaModel = $this->getNamaModel();
        $allowedFields = "";
        $no = 1;
        foreach ($this->fields as $key => $value) {
            if ($no == 1) {
                $allowedFields .= "'".$value['name_field']."'";
            } else {
                $allowedFields .= ", '".$value['name_field']."'";
            }
            $no++;
        }

        $columnOrder = "";
        $no = 1;
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                if ($no == 1) {
                    $columnOrder .= "'a.".$value['name_field']."'";
                } else {
                    $columnOrder .= ", 'a.".$value['name_field']."'";
                }
                $no++;
            }
        }

        $model = "@?php

namespace App\Models".$this->table['namespace'].";

use CodeIgniter\Model;

class ".$namaModel." extends Model
{
    protected \$DBGroup          = 'default';
    protected \$table            = '".$this->table['table']."';
    protected \$primaryKey       = '".$this->table['primary_key']."';
    protected \$useAutoIncrement = true;
    protected \$insertID         = 0;
    protected \$returnType       = 'array';
    protected \$useSoftDeletes   = true;
    protected \$protectFields    = true;
    protected \$allowedFields    = [".$allowedFields."];

    // Dates
    protected \$useTimestamps = true;
    protected \$dateFormat    = 'datetime';
    protected \$createdField  = '".$this->table['v_created_at']."';
    protected \$updatedField  = '".$this->table['v_updated_at']."';
    protected \$deletedField  = '".$this->table['v_deleted_at']."';

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
    protected \$afterDelete    = [];";

        $allFields = "";
        $no = 1;
        foreach ($this->fields as $key => $value) {
            if ($no == 1) {
                $allFields .= "a.".$value['name_field'];
            } else {
                $allFields .= ", a.".$value['name_field'];
            }
            $no++;
        }
$model .= "\n\n\tpublic function detail(\$where = [])
    {
        \$table = \$this->db->table(\$this->table.' a');
        \$table->select('".$allFields."');
        \$table->where(\$where);
        return \$table->get();
    }";

$model .= "\n}";
        if (!file_exists(ROOTPATH.'app/Models')) {
            mkdir(ROOTPATH.'app/Models', 775);
        }
        $pathModel = ROOTPATH.'app/Models/'.$namaModel.'.php';
        $model = str_replace('@?', '<?', $model);
        $create = fopen($pathModel, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $model);
        fclose($create);

        return $model;
    }
}