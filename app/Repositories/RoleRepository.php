<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository implements RoleRepositoryInterface
{
    protected $role;
    protected $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function getAllRoles()
    {
        return $this->role->where('name', '!=', 'developer')
            ->where(function($query) {
                $query->where('is_hidden', false)
                      ->orWhereNull('is_hidden');
            })
            ->orderBy('name')
            ->get();
    }

    public function getRoleById($id)
    {
        return $this->role->findOrFail($id);
    }

    public function getAllPermissions()
    {
        return $this->permission->orderBy('name')->get()->groupBy(function($item) {
            return explode('_', $item->name)[0];
        });
    }

    public function createRole(array $data)
    {
        $role = $this->role->create([
            'name' => $data['name'],
            'guard_name' => 'web'
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function updateRole($id, array $data)
    {
        $role = $this->getRoleById($id);
        
        $role->update([
            'name' => $data['name']
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function deleteRole($id)
    {
        $role = $this->getRoleById($id);
        return $role->delete();
    }
} 