<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $skills = [
        'Informatique' => ['Laravel', 'React', 'Python', 'Réseaux'],
        'Langues' => ['Anglais', 'Français', 'Espagnol'],
        'Musique' => ['Guitare', 'Piano', 'Chant'],
        'Cuisine' => ['Pâtisserie', 'Cuisine malgache'],
        'Bricolage' => ['Électricité', 'Menuiserie'],
        'Sport' => ['Natation', 'Football'],
    ];

    foreach ($skills as $categoryName => $skillNames) {
        $category = \App\Models\Category::where('name', $categoryName)->first();

        foreach ($skillNames as $skillName) {
            \App\Models\Skill::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($skillName)],
                ['category_id' => $category->id, 'name' => $skillName]
            );
        }
    }
}
}
