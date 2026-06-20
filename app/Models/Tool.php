<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Tool extends Model
{
    use HasFactory;

    public const HOME_INDEX_CACHE_KEY = 'home_tools_index';

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

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::HOME_INDEX_CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::HOME_INDEX_CACHE_KEY));
    }
}
