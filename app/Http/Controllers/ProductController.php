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
        return ((!$repo_product->image)
            ? response('', 404)
            : response($repo_product->image, 200, ['Content-Type' => 'image/jpg'])
        );
    }

    public function detail($product_id)
    {
        return (new ProductRepository)->detail($product_id);
    }
}
