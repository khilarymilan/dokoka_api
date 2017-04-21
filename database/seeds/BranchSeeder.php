<?php

use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $repo = new \App\Repositories\BranchRepository;
        $repo->clear();
        $repo->insertUpdateMultiple([
            [
                'name' => 'Hobbes and Landes BGC',
                'description' => 'Toys for all size and age',
                'address' => 'Lane O, Taguig, Metro Manila',
                'phone_number' => 8564639,
                'store_id' => 5,
                'latitude' => 14.5503301,
                'longitude' => 121.0503698,
            ],
            [
                'name' => 'R.O.X BGC',
                'description' => 'Explore the world',
                'address' => '11th Avenue Fort Bonifacio Global City, Taguig, Taguig, Metro Manila',
                'phone_number' => 8264232,
                'store_id' => 6,
                'latitude' => 14.5521392802915,
                'longitude' => 121.0510021802915,
            ],
            [
                'name' => 'Fully Booked BGC',
                'description' => 'Live in the wold of fantasies and fictions',
                'address' => 'Bonifacio Global City, 5th Ave, Taguig, 1634 Metro Manila',
                'phone_number' => 3,
                'store_id' => 7,
                'latitude' => 14.5497357,
                'longitude' => 121.0528107,
            ],
            [
                'name' => 'Hyundai Asia BGC',
                'description' => 'Driving all over the world',
                'address' => '16th Floor, BGC Corporate Center, 30th Street cor 11th Ave., Bonifacio Global City, Taguig, 1634 Metro Manila',
                'phone_number' => 2323434,
                'store_id' => 8,
                'latitude' => 14.5526179802915,
                'longitude' => 121.0546639802915,
            ],
            [
                'name' => 'Lyric Makati',
                'description' => 'Sing it out loud',
                'address' => 'Unit 3055, Glorietta 2, Ayala Center, Office Dr, Makati, 1224 Metro Manila',
                'phone_number' => 8151043,
                'store_id' => 9,
                'latitude' => 14.551069,
                'longitude' => 121.025017,
            ],
            [
                'name' => 'Oddysey Music Makati',
                'description' => 'CD DVD Video Store',
                'address' => 'Upper Ground Floor Sm MegaMall Building A, EDSA, corner J. Vargas Avenue, Ortigas Center, Mandaluyong, 1552 Metro Manila',
                'phone_number' => 6873597,
                'store_id' => 10,
                'latitude' => 14.585909,
                'longitude' => 121.056973,
            ],
            [
                'name' => 'The Body Shop Makati',
                'description' => 'Chain with house-brand bath & body products in a variety of scents, plus makeup, fragrances & more.',
                'address' => 'Rockwell Drive Corner Estrella Street Rockwell Center, Makati, Makati, Metro Manila',
                'phone_number' => 8981308,
                'store_id' => 11,
                'latitude' => 14.551964,
                'longitude' => 1121.024982,
            ],
        ]);
    }
}
