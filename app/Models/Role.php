<?php

namespace App\Models;

use App\Models\Traits\HasHiddenRecords;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasHiddenRecords;

    protected $fillable = [
        'name',
        'guard_name',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];
} 