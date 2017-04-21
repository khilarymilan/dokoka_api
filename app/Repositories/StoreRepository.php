<?php

namespace App\Repositories;

class StoreRepository extends Repository
{
    public function list()
    {
    	return $this->model->orderBy('name')->get();
    }

}
