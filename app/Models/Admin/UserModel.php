<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'user';
    protected $primaryKey   = 'user_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['user_username', 'user_password', 'user_aktif', 'user_deleted_at'];

    protected $useTimestamps = false;
    protected $createdField  = 'user_created_at';
    protected $updatedField  = 'user_updated_at';
    protected $deletedField  = 'user_deleted_at';

    public $column_order  = array(null, null, 'a.user_username', 'a.user_updated_at', 'a.user_aktif');
    public $column_search = array('a.user_username');
    public $order         = array('a.user_id' => 'desc');

    public function __construct($request)
    {
        parent::__construct();
        $this->request = $request;
        $this->dt = $this->db->table($this->table . ' a');
    }

    private function _get_datatables_query()
    {
        $this->dt->select('a.user_id, a.user_username, a.user_superadmin, a.user_updated_at, a.user_aktif, a.user_deleted_at, a.user_updated_at');
        $this->dt->where('a.user_superadmin', 2);
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
}
