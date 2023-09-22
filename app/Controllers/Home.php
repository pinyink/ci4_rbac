<?php

namespace App\Controllers;

use App\Models\HomeModel;
use App\Libraries\Tema;

class Home extends BaseController
{
	public function index()
	{
		if (empty(session('user'))) {
			return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-danger">Anda Tidak Mempunyai Akses</div>');
			die;
		}
		$data = [
			'isiContent' => "Hello Everyone, this is home page"
		];
		$tema = new Tema();
		$tema->setJudul('Dashboard');
		$tema->loadTema('tema/content', $data);
	}

	public function coba()
	{
		helper('Permission_helper');
		if (enforce(2, 2)) {
			echo 'akses';
		} else {
			echo 'denied';
		}
		$path = PUBLICPATH;
		echo '<img src="'.PUBLICPATH.'assets/admincast/dist/assets/img/404.png'.'">';
	}
}
