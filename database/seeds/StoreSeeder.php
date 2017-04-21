<?php

use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run()
    {

        \App\Models\Store::create([
            'name'=> 'The Body Shop',
            'active'=> 1,
        ]);
    }
}
