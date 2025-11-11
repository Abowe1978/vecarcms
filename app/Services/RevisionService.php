<?php

namespace App\Services;

use App\Models\Revision;
use App\Repositories\RevisionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RevisionService
{
    public function __construct(
        protected RevisionRepository $revisionRepository
    ) {}

    /**
     * Get all revisions for a model
     */
    public function getRevisionsFor(Model $model): Collection
    {
        return $this->revisionRepository->getForModel($model);
    }

    /**
     * Get manual revisions only
     */
    public function getManualRevisionsFor(Model $model): Collection
    {
        return $this->revisionRepository->getManualRevisionsForModel($model);
    }

    /**
     * Create a manual revision
     */
    public function createRevision(Model $model, ?string $title = null): Revision
    {
        try {
            $revision = $this->revisionRepository->create([
                'revisionable_type' => get_class($model),
                'revisionable_id' => $model->id,
                'user_id' => auth()->id(),
                'type' => 'manual',
                'title' => $title,
                'content' => $model->toArray(),
            ]);

            // Clean old revisions
            $maxRevisions = settings('page_builder_max_revisions', 10);
            $this->revisionRepository->deleteOldRevisions($model, $maxRevisions);

            Log::info('Revision created', [
                'model' => get_class($model),
                'model_id' => $model->id,
                'revision_id' => $revision->id,
            ]);

            return $revision;

        } catch (\Exception $e) {
            Log::error('Error creating revision', [
                'error' => $e->getMessage(),
                'model' => get_class($model),
            ]);
            
            throw $e;
        }
    }

    /**
     * Create an auto-save revision
     */
    public function createAutoSave(Model $model): Revision
    {
        try {
            // Get and delete previous auto-save for this model
            $oldAutoSaves = $this->revisionRepository->getForModel($model)
                ->where('type', 'auto_save');
            
            foreach ($oldAutoSaves as $oldRevision) {
                $this->revisionRepository->delete($oldRevision);
            }

            $revision = $this->revisionRepository->create([
                'revisionable_type' => get_class($model),
                'revisionable_id' => $model->id,
                'user_id' => auth()->id(),
                'type' => 'auto_save',
                'title' => 'Auto-save',
                'content' => $model->toArray(),
            ]);

            return $revision;

        } catch (\Exception $e) {
            Log::error('Error creating auto-save', [
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Restore from a revision
     */
    public function restoreFromRevision(Model $model, Revision $revision): bool
    {
        try {
            // Create a revision before restoring (backup)
            $this->createRevision($model, 'Before restore to revision #' . $revision->id);

            // Restore content
            $content = $revision->content;
            unset($content['id'], $content['created_at'], $content['updated_at'], $content['deleted_at']);

            $result = $model->update($content);

            Log::info('Restored from revision', [
                'model' => get_class($model),
                'model_id' => $model->id,
                'revision_id' => $revision->id,
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Error restoring from revision', [
                'error' => $e->getMessage(),
                'revision_id' => $revision->id,
            ]);
            
            throw $e;
        }
    }

    /**
     * Compare current model with revision
     */
    public function compare(Model $model, Revision $revision): array
    {
        $current = $model->toArray();
        $revisionContent = $revision->content;

        $differences = [];

        foreach ($current as $key => $value) {
            if (isset($revisionContent[$key]) && $revisionContent[$key] !== $value) {
                $differences[$key] = [
                    'current' => $value,
                    'revision' => $revisionContent[$key],
                    'changed' => true,
                ];
            }
        }

        return $differences;
    }

    /**
     * Delete a revision
     */
    public function deleteRevision(Revision $revision): bool
    {
        return $this->revisionRepository->delete($revision);
    }

    /**
     * Clean old revisions for a model
     */
    public function cleanOldRevisions(Model $model, ?int $keep = null): int
    {
        $keep = $keep ?? settings('page_builder_max_revisions', 10);
        return $this->revisionRepository->deleteOldRevisions($model, $keep);
    }
}

