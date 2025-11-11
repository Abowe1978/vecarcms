<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface AdminServiceInterface 
{
    public function getAllAdmins(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc');
    public function getAdminById($id);
    public function createAdmin(array $data, ?UploadedFile $profileImage = null);
    public function updateAdmin(User $admin, array $data, ?UploadedFile $profileImage = null);
    public function deleteAdmin(User $admin);
    public function getAvailableRoles();
    public function getAvailableRolesForEditing(User $admin);
    public function canEditAdmin(User $admin);
    public function canDeleteAdmin(User $admin);
} 