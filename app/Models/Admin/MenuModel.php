<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table         = 'menu';
    protected $primaryKey   = 'menu_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = ['menu_desc'];

    public function __construct()
    {
        parent::__construct();
        $this->dt = $this->db->table($this->table . ' a');
    }

    public function getMenuAkses($where = [])
    {
        $table = $this->db->table('menu_akses');
        $table->select('menu_akses_id, menu_akses_desc');
        $table->where($where);
        $table->orderBy('menu_akses_id', 'asc');
        return $table->get();
    }
}
