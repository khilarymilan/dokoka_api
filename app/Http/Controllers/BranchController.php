<?php

namespace App\Http\Controllers;

use App\Repositories\BranchRepository;
class BranchController extends Controller
{
    
    public function list($store_id)
    {
        return (new BranchRepository)->list($store_id);
    }
}
