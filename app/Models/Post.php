<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\PublishedScope;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author',
        'publish_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publish_at' => 'datetime',
    ];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublishedScope());
    }

    /**
     * Scope a query to only include unpublished post.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnPublished($query)
    {
        return $query->withoutGlobalScopes(
            [PublishedScope::class]
        )->where('publish_at', '<=', now());
    }
}
