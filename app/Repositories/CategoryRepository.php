<?php

namespace App\Repositories;

class CategoryRepository extends Repository
{
    public function list()
    {
    	return $this->model->select(['id', 'name'])->orderBy('name')->get();
    }

}
