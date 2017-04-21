<?php

namespace App\Http\Controllers;

use App\Repositories\StoreRepository;
class StoreController extends Controller
{
	public function list()
	{
		return (new StoreRepository)->list();
	}
}
