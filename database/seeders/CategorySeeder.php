<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $categories = ['Informatique', 'Langues', 'Musique', 'Cuisine', 'Bricolage', 'Sport'];

    foreach ($categories as $name) {
        \App\Models\Category::firstOrCreate(
            ['slug' => \Illuminate\Support\Str::slug($name)],
            ['name' => $name]
        );
    }
}
}
