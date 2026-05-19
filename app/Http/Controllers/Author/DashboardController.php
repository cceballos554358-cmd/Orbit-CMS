<?php
namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $myArticles = Article::where('user_id', auth()->id())
            ->with(['categories', 'tags'])
            ->latest()
            ->get();

        $counts = [
            'draft'     => Article::where('user_id', auth()->id())
                ->where('status', 'draft')->count(),
            'pending'   => Article::where('user_id', auth()->id())
                ->where('status', 'pending')->count(),
            'published' => Article::where('user_id', auth()->id())
                ->where('status', 'published')->count(),
        ];

        return view('author.dashboard', compact('myArticles', 'counts'));
    }
}