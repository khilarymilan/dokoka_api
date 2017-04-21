<?php
namespace App\Http\Controllers;

use App\Exceptions\ProductException;

class ProductController extends Controller
{
    public function index()
    {
        // do sometjhing
        // update product
        ProductException::throwEmptyName();
    }
}
