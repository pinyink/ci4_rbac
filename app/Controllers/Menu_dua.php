<?php

namespace App\Controllers;

use App\Libraries\Tema;
use CodeIgniter\Controller;

class Menu_dua extends Controller
{
    private $tema;

    public function __construct()
    {
        helper('Permission_helper');
        $this->tema = new Tema();
    }

    public function index()
    {
        $db = db_connect();

        $tables = $db->listTables();

        $arraytable = [];
        foreach ($tables as $table) {
            $fields = $db->getFieldData($table);

            $table = '<table style="width: 100%; border: 1px solid black;border-collapse: collapse;"><tr><td colspan="5"><b>Table '.$table.'</b></td></tr>';
            $table.='<tr>
                <td style="border: 1px solid black;border-collapse: collapse;">Nama Field</td>
                <td style="border: 1px solid black;border-collapse: collapse;">Type Data</td>
                <td style="border: 1px solid black;border-collapse: collapse;">Length</td>
                <td style="border: 1px solid black;border-collapse: collapse;">Primary Key</td>
                <td style="border: 1px solid black;border-collapse: collapse;">Null</td>
                <td style="border: 1px solid black;border-collapse: collapse;">Default</td>
            </tr>';
            foreach ($fields as $field) {
                $table.= '<tr>';
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$field->name.'</td>';
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$field->type.'</td>';
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$field->max_length.'</td>';
                $primary = $field->primary_key == 1 ? 'Ya' : 'Tidak';
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$primary.'</td>';
                $null = $field->nullable == true ? 'Ya' : 'Tidak';
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$null.'</td>';
                $default = $field->default;
                $table.='<td style="border: 1px solid black;border-collapse: collapse;">'.$default.'</td>';
                $table.='</tr>';
            }
            $table.='</table><br><br>';
            array_push($arraytable, $table);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            // 'format' => ['381', '677'],
            'format' => ['219', '330'],
            'orientation' => 'P',
            'default_font' => 'verdana',
            'allow_charset_conversion' => true,
            'list_auto_mode ' => 'browser',
        ]);

        // $mpdf->WriteHTML(implode("", $arraytable));
        foreach ($arraytable as $key => $value) {
            $mpdf->AddPage();
            $mpdf->WriteHTML($value);
        }
        $mpdf->Output('hello.pdf', 'I');
        exit();
    }

    public function createDb()
    {
        $db = db_connect();

        $tables = $db->listTables();

        $arraytable = [];
        foreach ($tables as $table) {
            $text = 'Table '.$table.' {<br>';
            $fields = $db->getFieldData($table);
            foreach ($fields as $field) {
                $text.= "&nbsp;&nbsp;&nbsp;&nbsp;".$field->name.' '.$field->type;
                if ($field->primary_key == 1) {
                    $text.=' [ pk ]';
                }
                $text.='<br>';
            }
            $text.='}';
            array_push($arraytable, $text);
        }

        echo implode('<br>', $arraytable);
    }
}
