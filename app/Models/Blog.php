<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'file', 'description'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
