<?php
namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {
        $pending   = Article::where('status', 'pending')->with('author')->latest()->get();
        $published = Article::where('status', 'published')->count();
        $comments  = Comment::where('is_approved', false)->count();

        return view('editor.dashboard', compact('pending', 'published', 'comments'));
    }
}