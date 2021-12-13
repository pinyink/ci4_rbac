<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class MenuAksesModel extends Model
{
    protected $table         = 'menu_akses';
    protected $primaryKey   = 'akses_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = ['menu_akses_id', 'menu_akses_desc', 'menu_id'];
}