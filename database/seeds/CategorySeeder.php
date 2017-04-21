<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        \App\Models\Category::create([
           	'name'=> 'Cars',
        ]);
    }
}
