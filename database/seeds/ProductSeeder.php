<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Product::create([
            'name'=>'product name',
            'price'=>1000,
            'details'=>'some details',
        ]);
    }
}
