<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'categories', 'tags'])
            ->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        if (auth()->check() && auth()->user()->hasRole(['admin', 'editor'])) {
            $comments = $article->comments()
                ->whereNull('parent_id')
                ->with(['author', 'replies.author'])
                ->latest()->get();
            return view('articles.show', compact('article', 'comments'));
        }

        if (auth()->check() && $article->user_id === auth()->id()) {
            $comments = $article->comments()
                ->whereNull('parent_id')
                ->with(['author', 'replies.author'])
                ->latest()->get();
            return view('articles.show', compact('article', 'comments'));
        }

        if ($article->status !== 'published') {
            abort(404);
        }

        $comments = $article->comments()
            ->whereNull('parent_id')
            ->with(['author', 'replies.author'])
            ->latest()->get();

        return view('articles.show', compact('article', 'comments'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags       = Tag::all();

        if (auth()->user()->hasRole('contributor')) {
            return view('contributor.drafts.create', compact('categories', 'tags'));
        }

        return view('articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'body'         => 'required|string',
            'thumbnail'    => 'nullable|image|max:2048',
            'tags'         => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug     = $baseSlug;
        $count    = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;

        // Contributors and Authors publish instantly to the public!
        if (auth()->user()->hasRole('subscriber')) {
            $validated['status'] = 'draft';
        } else {
            $validated['status']       = 'published';
            $validated['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        $categoryIds = $validated['categories'] ?? [];
        unset($validated['tags'], $validated['categories']);

        $article = Article::create($validated);

        // Sync multiple categories
        if (!empty($categoryIds)) {
            $article->categories()->sync($categoryIds);
        }

        // Handle tags
        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $tagIds   = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag      = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Article published successfully!');
    }

    public function edit(Article $article)
    {
        if (auth()->user()->hasRole(['admin', 'editor'])) {
            $categories = Category::all();
            $tags       = Tag::all();
            return view('articles.edit', compact('article', 'categories', 'tags'));
        }

        if ($article->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this article.');
        }

        $categories = Category::all();
        $tags       = Tag::all();
        return view('articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        if (!auth()->user()->hasRole(['admin', 'editor'])) {
            if ($article->user_id !== auth()->id()) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'body'         => 'required|string',
            'thumbnail'    => 'nullable|image|max:2048',
            'tags'         => 'nullable|string',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug     = $baseSlug . '-' . $article->id;
        $validated['slug'] = $slug;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Admin/editor can set status directly
        if (auth()->user()->hasRole(['admin', 'editor']) && $request->filled('status')) {
            $validated['status'] = $request->status;
        }

        $categoryIds = $validated['categories'] ?? [];
        unset($validated['tags'], $validated['categories']);

        $article->update($validated);

        // Sync multiple categories
        $article->categories()->sync($categoryIds);

        // Handle tags
        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $tagIds   = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag      = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Article updated successfully!');
    }

    public function destroy(Article $article)
    {
        if (!auth()->user()->hasRole('admin')) {
            if ($article->user_id !== auth()->id()) {
                abort(403, 'You do not have permission to delete this article.');
            }
        }

        if ($article->thumbnail) {
            \Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();
        
        return back()->with('success', 'Article deleted successfully.');
    }

    public function editorIndex()
    {
        $articles = Article::with(['author', 'categories', 'tags'])
            ->latest()->paginate(10);
        return view('editor.articles.index', compact('articles'));
    }

    public function updateStatus(Request $request, Article $article)
    {
        $request->validate([
            'status' => 'required|in:draft,pending,published'
        ]);

        $article->update([
            'status'       => $request->status,
            'published_at' => $request->status === 'published'
                ? now()
                : $article->published_at,
        ]);

        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function myArticles()
    {
        $articles = Article::where('user_id', auth()->id())
            ->with(['categories', 'tags'])
            ->latest()->paginate(10);
        return view('author.articles.index', compact('articles'));
    }

    public function myDrafts()
    {
        $articles = Article::where('user_id', auth()->id())
            ->with(['categories', 'tags'])
            ->latest()->paginate(10);
        return view('contributor.drafts.index', compact('articles'));
    }
}