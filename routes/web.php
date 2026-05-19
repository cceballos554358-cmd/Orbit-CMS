<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Editor\DashboardController as EditorDashboard;
use App\Http\Controllers\Author\DashboardController as AuthorDashboard;
use App\Http\Controllers\Subscriber\DashboardController as SubscriberDashboard;

// -------------------------------------------------------
// PUBLIC ROUTES
// -------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

// -------------------------------------------------------
// AUTH ROUTES
// -------------------------------------------------------
require __DIR__.'/auth.php';

// -------------------------------------------------------
// UNIVERSAL EDIT ROUTES
// -------------------------------------------------------
Route::middleware('auth')->group(function () {

    Route::get('/edit-my-article/{article}', function ($article) {
        $article    = App\Models\Article::findOrFail($article);
        if ($article->user_id !== auth()->id()
            && !auth()->user()->hasRole(['admin', 'editor'])) {
            abort(403);
        }
        $categories = App\Models\Category::all();
        $tags       = App\Models\Tag::all();
        return view('articles.edit', compact('article', 'categories', 'tags'));
    })->name('my.article.edit');

    Route::patch('/update-my-article/{article}', function (
        $article,
        \Illuminate\Http\Request $request
    ) {
        $article = App\Models\Article::findOrFail($article);
        if ($article->user_id !== auth()->id()
            && !auth()->user()->hasRole(['admin', 'editor'])) {
            abort(403);
        }

        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $newStatus = $article->status;
        if (auth()->user()->hasRole(['admin', 'editor'])) {
            $newStatus = $request->status ?? $article->status;
        }

        $article->update([
            'title'       => $request->title,
            'body'        => $request->body,
            'category_id' => $request->categories[0] ?? null,
            'status'      => $newStatus,
            'slug'        => \Illuminate\Support\Str::slug($request->title)
                             . '-' . $article->id,
        ]);

        if ($request->hasFile('thumbnail')) {
            $article->update([
                'thumbnail' => $request->file('thumbnail')
                    ->store('thumbnails', 'public')
            ]);
        }

        if ($request->filled('categories')) {
            $article->categories()->sync($request->categories);
        }

        if ($request->filled('tags')) {
            $tagNames = array_map('trim', explode(',', $request->tags));
            $tagIds   = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag      = App\Models\Tag::firstOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Article updated successfully!');
    })->name('my.article.update');
});

// -------------------------------------------------------
// PROTECTED ROUTES
// -------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Comments
    Route::post('/articles/{article}/comments',
        [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/report',
        [CommentController::class, 'report'])->name('comments.report');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::patch('/profile/password',
        [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
    Route::patch('/profile/role',
        [ProfileController::class, 'requestRole'])
        ->name('profile.requestRole');

    // Dashboard redirect — handles contributor too
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if (in_array($role, ['contributor'])) {
            $role = 'author';
        }
        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    // --------------------------------------------------
    // ADMIN
    // --------------------------------------------------
    Route::middleware('role:admin')
        ->prefix('admin')->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboard::class, 'index'])
                ->name('dashboard');
            Route::resource('users', UserController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('articles', ArticleController::class);
            Route::get('/comments', [CommentController::class, 'index'])
                ->name('comments.index');
            Route::patch('/comments/{comment}/approve',
                [CommentController::class, 'approve'])
                ->name('comments.approve');
            Route::delete('/comments/{comment}',
                [CommentController::class, 'destroy'])
                ->name('comments.destroy');
        });

    // --------------------------------------------------
    // EDITOR
    // --------------------------------------------------
    Route::middleware('role:admin,editor')
        ->prefix('editor')->name('editor.')
        ->group(function () {
            Route::get('/dashboard', [EditorDashboard::class, 'index'])
                ->name('dashboard');
            Route::get('/articles', [ArticleController::class, 'editorIndex'])
                ->name('articles.index');
            Route::patch('/articles/{article}/status',
                [ArticleController::class, 'updateStatus'])
                ->name('articles.status');
            Route::patch('/comments/{comment}/approve',
                [CommentController::class, 'approve'])
                ->name('comments.approve');
        });

    // --------------------------------------------------
    // AUTHOR (includes contributor)
    // --------------------------------------------------
    Route::middleware('role:admin,editor,author,contributor')
        ->prefix('author')->name('author.')
        ->group(function () {
            Route::get('/dashboard', [AuthorDashboard::class, 'index'])->name('dashboard');
            Route::get('/articles', [ArticleController::class, 'myArticles'])->name('articles.index');
            Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
            Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
            Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
            Route::patch('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
            Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
        });

    // --------------------------------------------------
    // CONTRIBUTOR — redirects to author dashboard
    // --------------------------------------------------
    Route::prefix('contributor')->name('contributor.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return redirect()->route('author.dashboard');
            })->name('dashboard');
        });

    // --------------------------------------------------
    // SUBSCRIBER
    // --------------------------------------------------
    Route::prefix('subscriber')->name('subscriber.')
        ->group(function () {
            Route::get('/dashboard',
                [SubscriberDashboard::class, 'index'])
                ->name('dashboard');
        });
});