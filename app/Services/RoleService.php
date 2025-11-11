<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Services\Interfaces\RoleServiceInterface;
use Illuminate\Support\Facades\Gate;

class RoleService implements RoleServiceInterface
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        return $this->roleRepository->getAllRoles();
    }

    public function getRoleById($id)
    {
        return $this->roleRepository->getRoleById($id);
    }

    public function getAllPermissions()
    {
        return $this->roleRepository->getAllPermissions();
    }

    public function createRole(array $data)
    {
        $this->validateAccess();
        
        return $this->roleRepository->createRole($data);
    }

    public function updateRole($id, array $data)
    {
        $role = $this->getRoleById($id);
        
        if (!$this->canUpdateRole($role)) {
            throw new \Exception(__('admin.roles.cannot_modify_protected_role'));
        }
        
        return $this->roleRepository->updateRole($id, $data);
    }

    public function deleteRole($id)
    {
        $role = $this->getRoleById($id);
        
        if (!$this->canDeleteRole($role)) {
            throw new \Exception(__('admin.roles.cannot_delete_protected_role'));
        }
        
        return $this->roleRepository->deleteRole($id);
    }

    public function canUpdateRole($role)
    {
        // Non consentire la modifica di ruoli protetti come developer
        if ($role->name === 'developer') {
            return false;
        }
        
        return Gate::allows('manage_roles');
    }

    public function canDeleteRole($role)
    {
        // Non consentire l'eliminazione di ruoli protetti e predefiniti
        if (in_array($role->name, ['developer', 'super-admin', 'admin'])) {
            return false;
        }
        
        return Gate::allows('manage_roles');
    }
    
    protected function validateAccess()
    {
        if (!Gate::allows('manage_roles')) {
            throw new \Exception(__('admin.roles.unauthorized'));
        }
    }
} 