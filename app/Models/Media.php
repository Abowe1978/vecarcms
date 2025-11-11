<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'path',
        'disk',
        'size',
        'user_id',
        'source',
    ];

    protected $appends = ['url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        // Assicuriamoci che l'URL inizi sempre con /storage/
        return '/storage/' . $this->path;
    }

    public function delete()
    {
        Storage::disk($this->disk)->delete($this->path);
        return parent::delete();
    }
} 