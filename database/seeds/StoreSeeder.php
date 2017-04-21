<?php

use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run()
    {
        $repo = new \App\Repositories\StoreRepository;
        $repo->clear();
        $repo->insertUpdateMultiple([
            ['name' => 'NIKE'],
            ['name' => 'Octagon'],
            ['name' => 'iStore'],
            ['name' => 'Hobbes and Landes'],
            ['name' => 'R.O.X'],
            ['name' => 'Fully Booked'],
            ['name' => 'Hyundai Asia'],
            ['name' => 'Lyric'],
            ['name' => 'Odyssey Music'],
            ['name' => 'The Body Shop'],
        ]);
    }
}
