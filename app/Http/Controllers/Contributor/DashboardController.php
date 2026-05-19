<?php
namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        // Get ALL articles by this contributor regardless of status
        $drafts = Article::where('user_id', auth()->id())
            ->latest()
            ->get();

        $stats = [
            'draft'     => Article::where('user_id', auth()->id())
                ->where('status', 'draft')->count(),
            'pending'   => Article::where('user_id', auth()->id())
                ->where('status', 'pending')->count(),
            'published' => Article::where('user_id', auth()->id())
                ->where('status', 'published')->count(),
        ];

        return view('contributor.dashboard', compact('drafts', 'stats'));
    }
}