<?php

namespace App\Repositories;

class ProductRepository extends Repository
{
    public function list($input = [])
    {
        return self::paginateSql(
            'getProductsList',
            [

            ],
            @$input['sort_by'],
            @$input['sort_ord'],
            @$input['page_num'],
            @$input['entries_per_page']
        );
    }
}
