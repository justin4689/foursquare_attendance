<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Enfant', 'description' => 'Moins de 13 ans'],
            ['name' => 'Jeune', 'description' => '13 à 25 ans'],
            ['name' => 'Homme', 'description' => 'Hommes adultes'],
            ['name' => 'Femme', 'description' => 'Femmes adultes'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
