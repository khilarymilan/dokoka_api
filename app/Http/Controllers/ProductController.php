<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        return (new ProductRepository)->list($request->input());
    }

    public function image(ProductRepository $repo_product)
    {
        dd($repo_product);
    }
}
