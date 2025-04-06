<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'name',
        'content',
        'files',
    ];

    protected function casts(): array
    {
        return [
            'files' => 'collection',
        ];
    }
}
