<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$this->display_view('welcome_message');
	}
}
