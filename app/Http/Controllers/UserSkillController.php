<?php

namespace App\Http\Controllers;

use App\Models\UserSkill;
use App\Models\Category;
use App\Http\Requests\StoreUserSkillRequest;

class UserSkillController extends Controller
{
    public function index()
    {
        $userSkills = auth()->user()->userSkills()->with('skill.category')->get();
        $categories = Category::with('skills')->get();

        return view('user-skills.index', compact('userSkills', 'categories'));
    }

    public function store(StoreUserSkillRequest $request)
    {
        auth()->user()->userSkills()->create($request->validated());

        return back()->with('success', 'Compétence ajoutée.');
    }

    public function destroy(UserSkill $userSkill)
    {
        abort_unless($userSkill->user_id === auth()->id(), 403);

        $userSkill->delete();

        return back()->with('success', 'Compétence supprimée.');
    }
}