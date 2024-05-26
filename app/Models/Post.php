<?php

namespace App\Models;

use App\Enums\Feeling;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory,Sluggable;

    protected $guarded = [];

    public function user() :BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function likes() :HasMany {
        return $this->hasMany(Like::class);
    }

    public function comments() :HasMany {
        return $this->hasMany(Comment::class)->latestFirst();
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'caption',
                'onUpdate' => false,
                'unique' => true
            ]
        ];
    }

}
