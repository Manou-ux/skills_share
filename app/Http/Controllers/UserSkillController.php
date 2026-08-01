<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\UserSkill;
use App\Http\Requests\StoreUserSkillRequest;

class UserSkillController extends Controller
{
    public function index()
    {
        $userSkills = auth()->user()->userSkills()->with('skill.category')->latest()->get();
        $categories = Category::with(['skills' => fn ($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('user-skills.index', compact('userSkills', 'categories'));
    }

    public function store(StoreUserSkillRequest $request)
    {
        $data = $request->validated();

        if ($data['type'] === 'besoin') {
            $data['niveau'] = null;
        }

        $exists = auth()->user()->userSkills()
            ->where('skill_id', $data['skill_id'])
            ->where('type', $data['type'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['skill_id' => 'Cette compétence est déjà enregistrée pour ce type.'])->withInput();
        }

        auth()->user()->userSkills()->create($data);

        $label = $data['type'] === 'offre' ? 'Offre' : 'Besoin';

        return redirect()->route('dashboard')->with('success', "{$label} publié(e) dans le fil d’actualité.");
    }

    public function destroy(UserSkill $userSkill)
    {
        abort_unless($userSkill->user_id === auth()->id(), 403);

        $userSkill->delete();

        return back()->with('success', 'Compétence supprimée.');
    }
}
