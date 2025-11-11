<?php

namespace App\Repositories\Interfaces;

interface ModeratorRepositoryInterface 
{
    public function getAllModerators(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc');
    public function getModeratorById($id);
    public function createModerator(array $data);
    public function updateModerator($id, array $data);
    public function deleteModerator($id);
} 