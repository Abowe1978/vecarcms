<?php

namespace App\Repositories;

use App\Models\Revision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RevisionRepository
{
    public function __construct(
        protected Revision $revision
    ) {}

    /**
     * Get all revisions for a model
     */
    public function getForModel(Model $model): Collection
    {
        return $this->revision
            ->where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get manual revisions for a model
     */
    public function getManualRevisionsForModel(Model $model): Collection
    {
        return $this->revision
            ->where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->id)
            ->manual()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find revision by ID
     */
    public function find(int $id): ?Revision
    {
        return $this->revision->with(['user', 'revisionable'])->find($id);
    }

    /**
     * Create a revision
     */
    public function create(array $data): Revision
    {
        return $this->revision->create($data);
    }

    /**
     * Delete a revision
     */
    public function delete(Revision $revision): bool
    {
        return $revision->delete();
    }

    /**
     * Delete old revisions for a model (keep last N)
     */
    public function deleteOldRevisions(Model $model, int $keep = 10): int
    {
        $revisions = $this->revision
            ->where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($revisions->count() <= $keep) {
            return 0;
        }

        $toDelete = $revisions->slice($keep);
        
        $deleted = 0;
        foreach ($toDelete as $revision) {
            if ($revision->delete()) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Get latest revision for model
     */
    public function getLatest(Model $model): ?Revision
    {
        return $this->revision
            ->where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->id)
            ->latest()
            ->first();
    }
}

