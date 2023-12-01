<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static latest(string $string)
 */
class Article extends Model
{
    use HasFactory;

//    protected $fillable = ['title', 'description', 'image', 'user_id'];
//    protected $guarded = [];
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upvotedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->wherePivot('type', 'upvote')->withTimestamps();
    }

    public function downvotedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->wherePivot('type', 'downvote')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
