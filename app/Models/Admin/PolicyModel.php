<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class PolicyModel extends Model
{
    protected $table         = 'policy';
    protected $primaryKey = 'policy_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['policy_desc'];

    protected $useTimestamps = false;
    protected $createdField  = 'policy_created_at';
    protected $updatedField  = 'policy_updated_at';
    protected $deletedField  = 'policy_deleted_at';

    public $column_order  = array(null, null, 'a.policy_desc');
    public $column_search = array('a.policy_desc');
    public $order         = array('a.policy_id' => 'desc');

    public function __construct($request)
    {
        parent::__construct();
        $this->request = $request;
        $this->dt = $this->db->table($this->table . ' a');
    }

    private function _get_datatables_query()
    {
        $this->dt->select('a.policy_id, a.policy_desc');

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                } else {
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->dt->groupEnd();
                }
            }
            $i++;
        }
        if ($this->request->getPost('order')) {
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($this->request->getPost('length') != -1) {
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }
        $query = $this->dt->get();
        return $query->getResult();
    }
    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }
    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }

    public function saveData($data)
    {
        $table = $this->db->table($this->table);
        $table->insert($data);
        return $this->db->affectedRows();
    }
}
