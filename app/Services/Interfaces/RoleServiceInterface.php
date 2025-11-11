<?php

namespace App\Services\Interfaces;

interface RoleServiceInterface 
{
    public function getAllRoles();
    public function getRoleById($id);
    public function getAllPermissions();
    public function createRole(array $data);
    public function updateRole($id, array $data);
    public function deleteRole($id);
    public function canUpdateRole($role);
    public function canDeleteRole($role);
} 