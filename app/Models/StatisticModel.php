<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class StatisticModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'statistic';
    protected $primaryKey       = 'statistic_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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

    public function updateJumlah()
    {
        $time = new Time('now');
        $id = $time->now()->toLocalizedString('YYYYMMdd');
        $query = $this->db->query("select * from statistic where statistic_id = '".$id."'");
        if ($query->getNumRows() >= 1) {
            $this->db->query("update statistic set total = total + 1 where statistic_id = '".$id."'");
        } else {
            $this->db->query("insert into statistic (statistic_id, statistic_date, total) values ('".$id."', '".date('Y-m-d')."', 1)");
        }
        return true;
    }

    public function statistik($bulan)
    {
        $month = date('Y-m', strtotime($bulan));

        // First day of the month.
        $first = date('1', strtotime($month));

        // Last day of the month.
        $last = date('t', strtotime($month));
        $text = '(';
        while ($first <= $last) {
            if ($first == 1) {
                $text .= "SELECT ".$first." AS tanggal UNION ALL ";
            } else {
                if ($first == $last) {
                    $text .= "SELECT ".$first;
                } else {
                    $text .= "SELECT ".$first." UNION ALL ";
                }
            }
            $first++;
        }
        $text .= ') AS MonthDate';

        return $this->db->query("SELECT MonthDate.tanggal, total FROM ".$text." left join statistic T1 on MonthDate.tanggal = DAY(T1.statistic_date) AND MONTH(T1.statistic_date) = ".date('m', strtotime($bulan))." and YEAR(T1.statistic_date) = ".date('Y', strtotime($bulan))." group by MonthDate.tanggal, total order by MonthDate.tanggal asc");
    }

    public function detailPengunjung($data)
    {
        $table = $this->db->table('statistic_detail');
        $table->insert($data);
        return $this->db->affectedRows();
    }

    public function online()
    {
        $time = date('Y-m-d H:i:s', strtotime("-5 minutes"));
        return $this->db->query("select count(*) total from (select ip_address from statistic_detail where created_at >= ".$this->db->escape($time)."
        group by ip_address) online");
    }

    public function totalSeluruh()
    {
        $query = $this->db->query('select sum(total) total from statistic');
        $row = $query->getRowArray();
        return $row['total'];
    }
}
