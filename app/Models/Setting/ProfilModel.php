<?php

namespace App\Models\Setting;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    public $table = "profil";

    public function getData($where = [])
    {
        $query = $this->db->table($this->table . ' a');
        $query->select("a.user_id, a.profil_firstname, a.profil_lastname, a.profil_email, a.profil_bio, a.profil_image");
        $query->where($where);
        $query->orderBy("a.user_id", "asc");
        return $query->get();
    }

    public function insertData($data)
    {
        $query = $this->db->table($this->table);
        $query->insert($data);
        return $this->db->affectedRows();
    }

    public function updateData($id, $data)
    {
        $query = $this->db->table($this->table);
        $query->where('user_id', $id);
        $query->update($data);
        return $this->db->affectedRows();
    }
}
