<?php

namespace App\Services;

use App\Models\Tag;
use App\Services\Interfaces\TagServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Collection;

class TagService implements TagServiceInterface
{
    /**
     * @var TagRepositoryInterface
     */
    protected $tagRepository;
    
    /**
     * Constructor
     */
    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
    
    /**
     * Get all tags with posts count
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTags(int $perPage = 15): LengthAwarePaginator
    {
        return Tag::withCount('posts')->orderBy('name')->paginate($perPage);
    }
    
    /**
     * Get a tag by ID
     *
     * @param int $id
     * @return Tag|null
     */
    public function getTagById(int $id): ?Tag
    {
        return Tag::find($id);
    }
    
    /**
     * Get popular tags with visualization data
     *
     * @param int $limit
     * @return array
     */
    public function getPopularTagsWithVisualization(int $limit = 20): array
    {
        $tags = $this->tagRepository->getPopularTags($limit);
        
        if ($tags->isEmpty()) {
            return [];
        }
        
        return $this->prepareTagsForVisualization($tags);
    }
    
    /**
     * Prepare tags for visualization with font sizes and colors
     *
     * @param Collection $tags
     * @return array
     */
    protected function prepareTagsForVisualization(Collection $tags): array
    {
        $maxCount = $tags->max('posts_count');
        $minCount = $tags->min('posts_count');
        $fontSizeRange = [14, 36]; // min and max font sizes
        
        // Colors for tag cloud (from lighter to darker)
        $colors = [
            '#818CF8', // Indigo
            '#6366F1',
            '#4F46E5',
            '#4338CA',
            '#3730A3',
        ];

        return $tags->map(function ($tag) use ($maxCount, $minCount, $fontSizeRange, $colors) {
            // Calculate font size based on post count
            $fontSize = $maxCount === $minCount
                ? $fontSizeRange[0]
                : $fontSizeRange[0] + (($tag->posts_count - $minCount) / ($maxCount - $minCount)) * ($fontSizeRange[1] - $fontSizeRange[0]);

            // Assign color based on frequency
            $colorIndex = $maxCount === $minCount
                ? 0
                : floor((($tag->posts_count - $minCount) / ($maxCount - $minCount)) * (count($colors) - 1));

            return [
                'name' => $tag->name,
                'count' => $tag->posts_count,
                'font_size' => round($fontSize, 1),
                'color' => $colors[$colorIndex],
            ];
        })->toArray();
    }
    
    /**
     * Validate tag name
     *
     * @param string $name
     * @return array
     */
    public function validateTagName(string $name): array
    {
        $errors = [];
        
        if (empty($name)) {
            $errors['required'] = __('admin.tags.validation.name_required');
        } elseif (strlen($name) < 2) {
            $errors['min'] = __('admin.tags.validation.name_min');
        } elseif (strlen($name) > 255) {
            $errors['max'] = __('admin.tags.validation.name_max');
        } elseif ($this->tagRepository->nameExists($name)) {
            $errors['unique'] = __('admin.tags.validation.name_unique');
        }
        
        return $errors;
    }
    
    /**
     * Create a new tag
     *
     * @param array $data Validated tag data
     * @return Tag
     */
    public function createTag(array $data): Tag
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        return $this->tagRepository->createTag($data);
    }
    
    /**
     * Parse tags string to array
     *
     * @param string $tagsString
     * @return array
     */
    public function parseTagsString(string $tagsString): array
    {
        return array_filter(array_map('trim', explode(',', $tagsString)));
    }
    
    /**
     * Format tags array to string
     *
     * @param array $tags
     * @return string
     */
    public function formatTagsToString(array $tags): string
    {
        $tags = array_filter(array_map('trim', $tags));
        return implode(', ', $tags);
    }
    
    /**
     * Update an existing tag
     *
     * @param Tag $tag
     * @param array $data Validated tag data
     * @return Tag
     */
    public function updateTag(Tag $tag, array $data): Tag
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $tag->update([
            'name' => $data['name'],
            'slug' => $data['slug']
        ]);
        
        return $tag;
    }
    
    /**
     * Delete a tag
     *
     * @param Tag $tag
     * @return bool
     */
    public function deleteTag(Tag $tag): bool
    {
        // Detach the tag from all posts
        $tag->posts()->detach();
        
        return $tag->delete();
    }
} 