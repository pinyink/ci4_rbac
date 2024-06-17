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
    protected \$afterDelete    = [];
    
    public \$column_order  = array(null, null, ".$columnOrder.");
    public \$column_search = array(".$columnOrder.");
    public \$order         = array('a.".$this->table['primary_key']."' => 'desc');

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
    }";

        $selectFields = "";
        $no = 1;
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                if ($no == 1) {
                    $selectFields .= "a.".$value['name_field'];
                } else {
                    $selectFields .= ", a.".$value['name_field'];
                }
                $no++;
            }
        }
$model .= "\n\n\tprivate function _getDatatablesQuery()
    {
        \$this->dt->select('a.".$this->table['primary_key'].", ".$selectFields."');
        \$this->dt->where('a.'.\$this->deletedField, null);
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
    }";

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