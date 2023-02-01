<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Master\DesaModel;
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

class DesaController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Desa');
        $this->tema->loadTema('/master/desa');
    }

    public function ajaxList()
    {
        $desaModel = new DesaModel();
        $desaModel->setRequest($this->request);
        $lists = $desaModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->id_desa;
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a><a href="javascript:;" class="text-red ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->ds;
			$row[] = $list->kec;
			$row[] = $list->kab;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $desaModel->countAll(),
                "recordsFiltered" => $desaModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $desaModel = new DesaModel();

        $method = $this->request->getPost('method');
        
		$imgimage = $this->request->getFile('val_image');

        $validation = [
            'val_ds' => 'required','val_kec' => 'required','val_kab' => 'required',
        ];

        
		if (!empty($_FILES['val_image']['name'])) {
			$validation['val_image'] = 'uploaded[val_image]'
			. '|is_image[val_image]'
			. '|mime_in[val_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
			. '|max_size[val_image,2048]';
		}
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
			if (!empty($_FILES['val_image']['name'])) {
				$type = $imgimage->getClientMimeType();
				$message .= '<li>'.$imgimage->getErrorString() . '(' . $imgimage->getError() . ' Type File ' . $type . ' )</li>';
			}
            $message .= '</ul>';

            $log['errorCode'] = 2;
            $log['errorMessage'] = $message;
            return $this->response->setJSON($log);
        }

        $id = $this->request->getPost('id_desa');
		if (!empty($_FILES['val_the_geom']['name'])) {
			$uniqId = uniqid();
			$th = date('Y') . '/' . date('m').'/'.date('d').'/'.$uniqId;
			$path = 'uploads/master/desa';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
				$create = fopen($dir.'/index.php', "w") or die("Change your permision folder for application and harviacode folder to 777");
				fwrite($create, '<h1>ACCESS DENIED</h1>');
				fclose($create);
			}
			$fileShp = $this->request->getFile('val_the_geom');
			$fileShp->move($dir, $uniqId.'.shp');
			$fileShx = $this->request->getFile('val_the_geom_shx');
			$fileShx->move($dir, $uniqId.'.shx');
			$fileDbf = $this->request->getFile('val_the_geom_dbf');
			$fileDbf->move($dir, $uniqId.'.dbf');
			$Shapefile = new ShapefileReader($dir.'/'.$uniqId.'.shp');
			$jsonData = '';
			while ($Geometry = $Shapefile->fetchRecord()) {
				$jsonData = $Geometry->getWKT();
			}
			$data['the_geom'] = new RawSql("ST_GeomFromText('".$jsonData."')");
		}
		$data['ds'] = $this->request->getPost('val_ds');
		$data['kec'] = $this->request->getPost('val_kec');
		$data['kab'] = $this->request->getPost('val_kab');
		if (!empty($_FILES['val_image']['name'])) {
			$th = date('Y') . '/' . date('m').'/'.date('d');
			$path = 'uploads/master/desa/';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = $imgimage->getRandomName();
			$imgimage->move($dir, $newName);
			$data['image'] = $_dir.'/'.$newName;
		}

        if ($method == 'save') {
			if(!isset($data['the_geom'])){
				$data['the_geom'] = new RawSql("ST_GeomFromText('POINT(0 0)')");
			}
            $desaModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $desaModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $desaModel = new DesaModel();
        $query = $desaModel->select('id_desa, ST_AsGeoJson(the_geom) as the_geom, ds, kec, kab, image')->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $desaModel = new DesaModel();
        $query = $desaModel->delete($id);
        if ($query) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Delete Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Delete Data Gagal';
            $log['errorType'] = 'warning';
            return $this->response->setJSON($log);
        }
    }

    
}
