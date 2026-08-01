<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $slug = Str::slug($data['name']);
        $base = $slug ?: 'categorie';
        $unique = $base;
        $i = 1;
        while (Category::where('slug', $unique)->exists()) {
            $unique = $base.'-'.$i++;
        }

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $unique,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', "Domaine « {$category->name} » créé. Vous pouvez y ajouter des skills.")->with('focus_category', $category->id);
    }

    public function storeSkill(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $slug = Str::slug($data['name']);
        $base = $slug ?: 'skill';
        $unique = $base;
        $i = 1;
        while (Skill::where('slug', $unique)->exists()) {
            $unique = $base.'-'.$i++;
        }

        $skill = Skill::create([
            'name' => $data['name'],
            'slug' => $unique,
            'category_id' => $data['category_id'],
            'created_by' => auth()->id(),
        ]);

        return back()
            ->with('success', "Skill « {$skill->name} » ajouté. Vous pouvez maintenant le publier en offre ou besoin.")
            ->withInput(['skill_id' => $skill->id]);
    }
}
