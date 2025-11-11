<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Commentable;
use App\Models\Traits\HasSeo;
use App\Models\Traits\HasRevisions;

class Post extends Model
{
    use HasFactory, SoftDeletes, Commentable, HasSeo, HasRevisions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'published_at',
        'author_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'page_builder_content',
        'use_page_builder',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'page_builder_content' => 'array',
        'use_page_builder' => 'boolean',
    ];

    /**
     * Get the author of the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Ottieni l'URL dell'immagine in evidenza
     */
    public function getImageUrl()
    {
        if (empty($this->featured_image)) {
            return null;
        }
        
        // Se è un URL relativo che inizia con /storage, restituiscilo così com'è
        if (str_starts_with($this->featured_image, '/storage')) {
            return $this->featured_image;
        }
        
        // Se è solo un nome file, aggiungi il percorso
        if (!str_starts_with($this->featured_image, 'http') && !str_starts_with($this->featured_image, '/')) {
            return '/storage/uploads/' . $this->featured_image;
        }
        
        // Altrimenti restituisci com'è
        return $this->featured_image;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }

    /**
     * Generate a unique slug like WordPress does
     */
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        // Check if slug exists
        while (static::slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists
     */
    protected static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = static::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get related posts based on categories and tags
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedPosts(int $limit = 3)
    {
        // Get category IDs
        $categoryIds = $this->categories->pluck('id')->toArray();
        
        // Get tag IDs
        $tagIds = $this->tags->pluck('id')->toArray();
        
        if (empty($categoryIds) && empty($tagIds)) {
            // If no categories or tags, return latest posts
            return static::where('id', '!=', $this->id)
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        }
        
        // Find posts with similar categories or tags
        return static::where('id', '!=', $this->id)
            ->where('status', 'published')
            ->where(function($query) use ($categoryIds, $tagIds) {
                if (!empty($categoryIds)) {
                    $query->whereHas('categories', function($q) use ($categoryIds) {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                }
                
                if (!empty($tagIds)) {
                    $query->orWhereHas('tags', function($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
                }
            })
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
