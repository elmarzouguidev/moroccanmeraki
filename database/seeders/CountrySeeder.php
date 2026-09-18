<?php

namespace Database\Seeders;

use App\Models\Tools\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Morocco', 'code' => 'MA'],
        ];

        foreach ($cities as $city) {
            Country::create($city);
        }
    }
}
