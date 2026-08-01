<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\ExchangeRequest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'skillsCount' => Skill::count(),
            'categoriesCount' => Category::count(),
            'requestsCount' => ExchangeRequest::count(),
            'pendingCount' => ExchangeRequest::where('status', 'en_attente')->count(),
            'conversationsCount' => Conversation::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentRequests' => ExchangeRequest::with(['sender', 'receiver', 'skill'])->latest()->take(5)->get(),
        ]);
    }

    public function users(Request $request)
    {
        $query = User::withCount(['userSkills', 'sentRequests', 'receivedRequests']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->city.'%');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function categories(Request $request)
    {
        $query = Category::with(['skills' => fn ($q) => $q->orderBy('name')])->withCount('skills');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('skills', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $categories = $query->orderBy('name')->get();

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $slug = Str::slug($data['name']) ?: 'categorie';
        $unique = $slug;
        $i = 1;
        while (Category::where('slug', $unique)->exists()) {
            $unique = $slug.'-'.$i++;
        }

        Category::create([
            'name' => $data['name'],
            'slug' => $unique,
        ]);

        return back()->with('success', 'Catégorie ajoutée.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    public function storeSkill(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $slug = Str::slug($data['name']) ?: 'skill';
        $unique = $slug;
        $i = 1;
        while (Skill::where('slug', $unique)->exists()) {
            $unique = $slug.'-'.$i++;
        }

        Skill::create([
            'name' => $data['name'],
            'slug' => $unique,
            'category_id' => $data['category_id'],
        ]);

        return back()->with('success', 'Compétence ajoutée.')->with('open_category', $data['category_id']);
    }

    public function destroySkill(Skill $skill)
    {
        $categoryId = $skill->category_id;
        $skill->delete();

        return back()->with('success', 'Compétence supprimée.')->with('open_category', $categoryId);
    }

    public function requests(Request $request)
    {
        $query = ExchangeRequest::with(['sender', 'receiver', 'skill']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('sender', fn ($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('receiver', fn ($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('skill', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.requests', compact('requests'));
    }

    public function destroyRequest(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->delete();

        return back()->with('success', 'Demande supprimée.');
    }

    public function userSkills(Request $request)
    {
        $query = UserSkill::with(['user', 'skill.category']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('skill', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->whereHas('skill', fn ($s) => $s->where('category_id', $request->category));
        }

        $userSkills = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.user-skills', compact('userSkills', 'categories'));
    }

    public function destroyUserSkill(UserSkill $userSkill)
    {
        $userSkill->delete();

        return back()->with('success', 'Lien compétence/utilisateur supprimé.');
    }
}
