<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() :BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function facebook() {
        return 'https://www.facebook.com/'.$this->facebook;
    }

    public function linkedin() {
        return 'https://www.linkedin.com/in/'.$this->linkedin;
    }

    public function github() {
        return 'https://www.github.com/'.$this->github;
    }

}
