<?php

namespace App\Repositories;

class ProductRepository extends Repository
{
    public function list($input = [])
    {
        $from_lat = $from_lng = null;

        if(@$input['latlng']) {
            list($from_lat, $from_lng) = explode(',', @$input['latlng']);
        }

        return self::paginateSql(
            'getProductsList',
            [
                'FROM_LAT' => $from_lat,
                'FROM_LNG' => $from_lng,
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
