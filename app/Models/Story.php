<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'genre',
        'description',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function versions()
    {
        return $this->hasMany(StoryVersion::class)->orderBy('created_at', 'desc');
    }

    public function collaborators()
    {
        return $this->hasMany(StoryCollaborator::class);
    }

    public function isCollaborator($userId)
    {
        return $this->collaborators()->where('user_id', $userId)->exists();
    }

    public function canEdit($userId)
    {
        return $this->user_id === $userId || $this->isCollaborator($userId);
    }
}
