<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'author',
        'author_uri',
        'plugin_uri',
        'directory',
        'main_file',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];
}
