<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'files', 'description'];

    protected function casts(): array
    {
        return [
            'files' => 'collection',
        ];
    }
}
