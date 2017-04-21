<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $repo = new \App\Repositories\CategoryRepository;
        $repo->clear();
        $repo->insertUpdateMultiple([
            ['name' => 'Cars'],
            ['name' => 'Mobiles & Tablets'],
            ['name' => 'Games & Consoles'],
            ['name' => 'Fashion'],
            ['name' => 'Toys'],
            ['name' => 'Sports & Travel'],
            ['name' => 'Computer & Laptops'],
            ['name' => 'Cameras'],
            ['name' => 'Media, Music & Books'],
            ['name' => 'Health & Beauty'],
            ['name' => 'Cars'],
        ]);
    }
}
