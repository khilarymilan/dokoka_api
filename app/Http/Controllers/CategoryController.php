<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
class CategoryController extends Controller
{
	public function list()
	{
		return (new CategoryRepository)->list();
	}
}
