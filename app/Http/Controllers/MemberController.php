<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id())
            ->with(['userSkills.skill.category']);

        if ($request->filled('category')) {
            $query->whereHas('userSkills.skill', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('skill')) {
            $query->whereHas('userSkills', function ($q) use ($request) {
                $q->where('skill_id', $request->skill);
            });
        }

        if ($request->filled('type')) {
            $query->whereHas('userSkills', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhereHas('userSkills.skill', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('userSkills.skill.category', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $members = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::with(['skills' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();

        return view('members.index', compact('members', 'categories', 'skills'));
    }

    public function show(User $user)
    {
        abort_if($user->id === auth()->id(), 404);

        $user->load('userSkills.skill.category');

        return view('members.show', compact('user'));
    }
}
