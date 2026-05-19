<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with([
            'author',
            'category',
            'categories',
            'tags',
        ])->where('status', 'published');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('body',  'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $articles   = $query->latest('published_at')
                            ->paginate(9)
                            ->withQueryString();

$categories = Category::withCount('articlesList')->get();
       
$stats = [
            'published'  => Article::where('status', 'published')->count(),
            'categories' => Category::count(),
            'users'      => User::count(),
            'comments'   => Comment::count(),
        ];

        return view('home', compact('articles', 'categories', 'stats'));
    }
}