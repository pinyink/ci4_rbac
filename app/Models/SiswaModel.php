<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'siswa';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['siswa_nama', 'siswa_alamat', 'siswa_tempat_lahir', 'siswa_tanggal_lahir'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'siswa_created_at';
    protected $updatedField  = 'siswa_updated_at';
    protected $deletedField  = 'siswa_deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public $column_order  = array(null, null, 'a.siswa_nama', 'a.siswa_alamat', 'a.siswa_tempat_lahir', 'a.siswa_tanggal_lahir');
    public $column_search = array('a.siswa_nama', 'a.siswa_alamat', 'a.siswa_tempat_lahir', 'a.siswa_tanggal_lahir');
    public $order         = array('a.siswa_id' => 'desc');

    private $request = '';
    private $dt;
    private $where = [];
    
    public function setWhere($where = [])
    {
        $this->where = $where;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function initDatatables()
    {
        $this->dt = $this->db->table($this->table . ' a');
    }

    private function _getDatatablesQuery()
    {
        $this->dt->select('a.siswa_id, a.siswa_nama, a.siswa_alamat, a.siswa_tempat_lahir, a.siswa_tanggal_lahir');
        $this->dt->where($this->deletedField, null);
        $this->dt->where($this->where);
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($this->request->getPost('search')['value'])) {
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

    public function getDatatables()
    {
        $this->initDatatables();
        $this->_getDatatablesQuery();
        if ($this->request->getPost('length') != -1) {
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered()
    {
        $this->initDatatables();
        $this->_getDatatablesQuery();
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tblStorage = $this->db->table($this->table);
        return $tblStorage->countAllResults();
    }

    public function detail($where = [])
    {
        $table = $this->db->table($this->table.' a');
        $table->select('a.siswa_id, a.siswa_nama, a.siswa_alamat, a.siswa_tempat_lahir, a.siswa_tanggal_lahir');
        $table->where($where);
        return $table->get();
    }
}
