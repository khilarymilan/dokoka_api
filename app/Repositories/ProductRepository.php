<?php

namespace App\Repositories;

class ProductRepository extends Repository
{
    public function list($input = [])
    {
        return self::paginateSql(
            'getProductsList',
            [
                'LATLNG' => @$input['latlng'],
                'SEARCH_KEYWORDS' => @$input['search'],
                'CATEGORY_ID' => @$input['category_id'],
            ],
            @$input['sort_by'],
            @$input['sort_ord'],
            @$input['page_num'],
            @$input['entries_per_page']
        );
    }

    public function detail($product_id = null)
    {
        return $this->model->join('branches', 'branches.id', '=', 'products.branch_id')->where('products.id', $product_id)->first();
    }

}
