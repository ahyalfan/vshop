<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categories::create([
            "name"=> "Dell",
            "slug"=> "dell",
        ]);
        Categories::create([
            "name"=> "Samsung",
            "slug"=> "samsung",
        ]);
        Categories::create([
            "name"=> "Apple",
            "slug"=> "apple",
        ]);
    }
}
