<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    // Single category relationship (backwards compatibility)
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Many-to-many relationship
    public function articlesList()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }
}