<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $query = Comment::with(['author', 'article'])
            ->whereNull('parent_id')
            ->latest();

        if (request('filter') === 'reported') {
            $query->where('is_reported', true);
        }

        $comments = $query->paginate(15);
        $reported = Comment::where('is_reported', true)->count();

        return view('admin.comments.index', compact('comments', 'reported'));
    }

    public function store(Request $request, Article $article)
    {
        $request->validate([
            'body'      => 'nullable|string|max:1000',
            'media'     => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if (empty($request->body) && !$request->hasFile('media')) {
            return back()->withErrors([
                'body' => 'Please write a comment or attach an image.'
            ]);
        }

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $file      = $request->file('media');
            $extension = strtolower($file->getClientOriginalExtension());
            $mediaType = $extension === 'gif' ? 'gif' : 'image';
            $mediaPath = $file->store('comments', 'public');
        }

        $body = $request->body
            ? $this->filterBadWords($request->body)
            : null;

        Comment::create([
            'article_id'   => $article->id,
            'user_id'      => auth()->id(),
            'parent_id'    => $request->parent_id ?? null,
            'body'         => $body,
            'is_approved'  => true,
            'is_reported'  => false,
            'report_count' => 0,
            'media_path'   => $mediaPath,
            'media_type'   => $mediaType,
        ]);

        return back()->with('success', $request->parent_id
            ? 'Reply posted!'
            : 'Comment posted!');
    }

    public function approve(Comment $comment)
    {
        // Clear the report flag and reset count
        $comment->update([
            'is_reported'  => false,
            'report_count' => 0,
        ]);
        return back()->with('success', 'Report cleared. Comment is visible again.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->media_path) {
            \Storage::disk('public')->delete($comment->media_path);
        }
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }

    public function report(Comment $comment)
    {
        if ($comment->user_id === auth()->id()) {
            return back()->with('error', 'You cannot report your own comment.');
        }

        if ($comment->is_reported &&
            $comment->report_count >= Comment::REPORT_THRESHOLD) {
            return back()->with('error',
                'This comment has already been hidden from public.');
        }

        $newCount = $comment->report_count + 1;
        $hidden   = $newCount >= Comment::REPORT_THRESHOLD;

        $comment->update([
            'is_reported'  => true,
            'report_count' => $newCount,
        ]);

        if ($hidden) {
            return back()->with('success',
                'Comment reported. It has now been hidden from public view ' .
                'after reaching 3 reports. Admins have been notified.');
        }

        $remaining = Comment::REPORT_THRESHOLD - $newCount;
        return back()->with('success',
            'Comment reported. ' . $remaining . ' more report(s) needed to hide it.');
    }

    private function filterBadWords(string $text): string
    {
        $badWords = ['spam', 'scam', 'hate', 'abuse'];
        foreach ($badWords as $word) {
            $text = str_ireplace(
                $word,
                str_repeat('*', strlen($word)),
                $text
            );
        }
        return $text;
    }
}