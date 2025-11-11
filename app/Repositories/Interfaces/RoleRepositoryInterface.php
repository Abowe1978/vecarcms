<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface 
{
    public function getAllRoles();
    public function getRoleById($id);
    public function getAllPermissions();
    public function createRole(array $data);
    public function updateRole($id, array $data);
    public function deleteRole($id);
} 