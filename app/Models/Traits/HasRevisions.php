<?php

namespace App\Models\Traits;

use App\Models\Revision;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRevisions
{
    /**
     * Boot the trait
     */
    protected static function bootHasRevisions(): void
    {
        // Create revision on update
        static::updated(function ($model) {
            $model->createRevision('manual');
        });
    }

    /**
     * Get all revisions
     */
    public function revisions(): MorphMany
    {
        return $this->morphMany(Revision::class, 'revisionable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get manual revisions only
     */
    public function manualRevisions(): MorphMany
    {
        return $this->morphMany(Revision::class, 'revisionable')
            ->manual()
            ->orderBy('created_at', 'desc');
    }

    /**
     * Create a revision
     */
    public function createRevision(string $type = 'manual', ?string $title = null): Revision
    {
        // Get max revisions from settings
        $maxRevisions = settings('page_builder_max_revisions', 10);

        // Delete old revisions if limit exceeded
        $revisionsCount = $this->revisions()->count();
        
        if ($revisionsCount >= $maxRevisions) {
            $this->revisions()
                ->orderBy('created_at', 'asc')
                ->limit($revisionsCount - $maxRevisions + 1)
                ->delete();
        }

        // Create new revision
        return $this->revisions()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'title' => $title,
            'content' => $this->getRevisionContent(),
        ]);
    }

    /**
     * Get content for revision (override in model if needed)
     */
    protected function getRevisionContent(): array
    {
        return $this->toArray();
    }

    /**
     * Restore from a revision
     */
    public function restoreFromRevision(Revision $revision): bool
    {
        $content = $revision->content;

        // Remove fields that shouldn't be restored
        unset($content['id'], $content['created_at'], $content['updated_at'], $content['deleted_at']);

        return $this->update($content);
    }

    /**
     * Get latest revision
     */
    public function latestRevision(): ?Revision
    {
        return $this->revisions()->first();
    }

    /**
     * Compare with a revision
     */
    public function compareWithRevision(Revision $revision): array
    {
        $current = $this->toArray();
        $revisionContent = $revision->content;

        $differences = [];

        foreach ($current as $key => $value) {
            if (isset($revisionContent[$key]) && $revisionContent[$key] !== $value) {
                $differences[$key] = [
                    'current' => $value,
                    'revision' => $revisionContent[$key],
                ];
            }
        }

        return $differences;
    }
}

