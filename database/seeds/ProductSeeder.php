<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Product::create([
           	'name'=> 'Rayban Aviatior Glasses',
            'price'=> 5000,
            'details'=>'High quality solid color lenses, providing great clarity of vision, comfort and protection.',
            'available' => 3,
            'category_id' => 3,
        ]);

        \App\Models\Product::create([
			'name'=> 'Rayban Original Wayfarer Classic',
            'price'=> 4500,
            'details'=>'Solid colors, genuine since 1937, the classic G-15 was originally developed for military use, offers a high level of clarity, comfort and protection.',
            'available' => 2,
            'category_id' => 3,
        ]);
    }
}
