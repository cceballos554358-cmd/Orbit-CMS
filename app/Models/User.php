<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isEditor(): bool
{
    return $this->role === 'editor';
}

public function isAuthor(): bool
{
    return in_array($this->role, ['author', 'contributor']);
}

public function hasRole(string|array $roles): bool
{
    $normalized = array_map(
        fn($r) => $r === 'contributor' ? 'author' : $r,
        (array) $roles
    );
    $userRole = $this->role === 'contributor' ? 'author' : $this->role;
    return in_array($userRole, $normalized);
}
}