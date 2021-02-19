<?php

namespace Welcome\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
	public function index()
	{
		$data['title'] = "Welcome";
		$this->display_view('Welcome\welcome_message', $data);
	}

}
