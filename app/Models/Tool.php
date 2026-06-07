<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_name',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'json_ld',
    ];

    protected $casts = [
        'json_ld' => 'array',
    ];
}
