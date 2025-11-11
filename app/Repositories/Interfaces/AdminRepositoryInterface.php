<?php

namespace App\Repositories\Interfaces;

interface AdminRepositoryInterface 
{
    public function getAllAdmins(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc');
    public function getAdminById($id);
    public function createAdmin(array $data);
    public function updateAdmin($id, array $data);
    public function deleteAdmin($id);
    public function getAvailableRoles();
} 