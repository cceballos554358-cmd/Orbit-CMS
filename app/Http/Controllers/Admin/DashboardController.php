<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'      => User::count(),
            'articles'   => Article::count(),
            'published'  => Article::where('status', 'published')->count(),
            'pending'    => Article::where('status', 'pending')->count(),
            'comments'   => Comment::count(),
            'categories' => Category::count(),
        ];

        $latestArticles = Article::with('author')->latest()->take(5)->get();
        $latestUsers    = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestArticles', 'latestUsers'));
    }
}