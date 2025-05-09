<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Country;

class BrandSeeder extends Seeder
{
    public function run()
    {
        if (Brand::count() > 0) Brand::truncate();
        Brand::factory()->count(100)->create();
    }
} 