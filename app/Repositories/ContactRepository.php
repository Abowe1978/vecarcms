<?php

namespace App\Repositories;

use App\Models\ContactSubmission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository
{
    public function __construct(
        protected ContactSubmission $contactSubmission
    ) {}

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->contactSubmission
            ->with('readByUser')
            ->latest()
            ->paginate($perPage);
    }

    public function getNew(int $perPage = 20): LengthAwarePaginator
    {
        return $this->contactSubmission
            ->new()
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?ContactSubmission
    {
        return $this->contactSubmission->find($id);
    }

    public function create(array $data): ContactSubmission
    {
        return $this->contactSubmission->create($data);
    }

    public function update(ContactSubmission $submission, array $data): bool
    {
        return $submission->update($data);
    }

    public function delete(ContactSubmission $submission): bool
    {
        return $submission->delete();
    }

    public function getCountByStatus(): array
    {
        return [
            'new' => $this->contactSubmission->new()->count(),
            'read' => $this->contactSubmission->read()->count(),
            'all' => $this->contactSubmission->count(),
        ];
    }
}

