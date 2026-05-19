<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'status',
        'thumbnail',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Single category (backwards compatibility)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Multiple categories (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    // Tags (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    // Author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Media
    public function media()
    {
        return $this->hasMany(ArticleMedia::class);
    }

    // Scope: published only
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}