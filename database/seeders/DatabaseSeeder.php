<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\ProductTranslation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
          \App\Models\Vendor::factory(10)->create();
          \App\Models\Product::factory(10)->create();
          \App\Models\ProductTranslation::factory(10)->create();
    }
}
