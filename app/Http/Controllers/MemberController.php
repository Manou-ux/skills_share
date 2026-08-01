<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('id', '!=', auth()->id())
            ->with('userSkills.skill')
            ->paginate(12);

        $categories = Category::all();

        return view('members.index', compact('members', 'categories'));
    }

    public function show(User $user)
    {
        $user->load('userSkills.skill.category');

        return view('members.show', compact('user'));
    }
}