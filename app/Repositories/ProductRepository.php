<?php

namespace App\Repositories;

class ProductRepository extends Repository
{
    public function list($input = [])
    {
        $latlng = $from_lat = $from_lng = null;

        if (@$input['location']) {
            $latlng = json_decode(file_get_contents(
                'https://maps.googleapis.com/maps/api/geocode/json?address=' .
                urlencode($input['location']) .
                '&key=AIzaSyAqu354Ybp1UTNsas5RrWmn16ymBnh4Dv0'
            ), true);
            $latlng = $latlng['results'][0]['geometry']['location'];
            $from_lat = $latlng['lat'];
            $from_lng = $latlng['lng'];
        }

        return self::paginateSql(
            'getProductsList',
            [
                'FROM_LAT' => $from_lat,
                'FROM_LNG' => $from_lng,
                'LATLNG' => $latlng,
                'SEARCH_KEYWORDS' => @$input['search'],
                'CATEGORY_ID' => @$input['category_id'],
                'PRICE_MIN' => @$input['price_min'],
                'PRICE_MAX' => @$input['price_max'],
            ],
            @$input['sort_by'] ?: 'relevance',
            @$input['sort_ord'] ?: 'desc',
            @$input['page_num'],
            @$input['entries_per_page']
        );
    }

    public function detail($product_id = null, $input = [])
    {
        $latlng = $from_lat = $from_lng = null;

        if (@$input['location']) {
            $latlng = json_decode(file_get_contents(
                'https://maps.googleapis.com/maps/api/geocode/json?address=' .
                urlencode($input['location']) .
                '&key=AIzaSyAqu354Ybp1UTNsas5RrWmn16ymBnh4Dv0'
            ), true);
            $latlng = $latlng['results'][0]['geometry']['location'];
            $from_lat = $latlng['lat'];
            $from_lng = $latlng['lng'];
        }

        return self::selectSql(
            'getProductsList',
            [
                'PRODUCT_ID' => $product_id,
                'FROM_LAT' => $from_lat,
                'FROM_LNG' => $from_lng,
                'LATLNG' => $latlng,
            ]
        );
    }
}
