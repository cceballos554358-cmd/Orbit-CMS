<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'body',
        'is_approved',
        'is_reported',
        'report_count',
        'media_path',
        'media_type',
    ];

    const REPORT_THRESHOLD = 3;

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with('author')
                    ->latest();
    }

    public function hasMedia(): bool
    {
        return !empty($this->media_path);
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    public function isGif(): bool
    {
        return $this->media_type === 'gif';
    }

    public function isHiddenFromPublic(): bool
    {
        return $this->report_count >= self::REPORT_THRESHOLD;
    }

    public function isFlagged(): bool
    {
        return $this->is_reported && $this->report_count < self::REPORT_THRESHOLD;
    }
}