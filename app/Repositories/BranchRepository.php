<?php

namespace App\Repositories;

class BranchRepository extends Repository
{
    public function list($store_id)
    {
    	return $this->model->join('stores', 'stores.id', '=', 'branches.store_id')->where('store_id', $store_id)->get();
    }

}
