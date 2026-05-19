<?php
namespace App\Http\Controllers\Subscriber;

use App\Http\Controllers\Controller;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $articles = Article::published()->latest('published_at')->take(6)->get();
        return view('subscriber.dashboard', compact('articles'));
    }
}