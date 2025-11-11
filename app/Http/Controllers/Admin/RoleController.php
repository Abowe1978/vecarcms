<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\Interfaces\RoleServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;

    protected $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
        $this->middleware('can:manage_roles');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        $groupedPermissions = $this->roleService->getAllPermissions();
        $permissions = collect($groupedPermissions->flatten());
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groupedPermissions = $this->roleService->getAllPermissions();
        
        return view('admin.roles.create', compact('groupedPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = $this->roleService->createRole($request->validated());
            
            return redirect()->route('admin.roles.index')
                ->with('success', __('admin.roles.created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = $this->roleService->getRoleById($id);
        
        if (!$this->roleService->canUpdateRole($role)) {
            return redirect()->route('admin.roles.index')
                ->with('error', __('admin.roles.cannot_modify_protected_role'));
        }
        
        $groupedPermissions = $this->roleService->getAllPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $this->roleService->updateRole($id, $request->validated());
            
            return redirect()->route('admin.roles.index')
                ->with('success', __('admin.roles.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->roleService->deleteRole($id);
            
            return redirect()->route('admin.roles.index')
                ->with('success', __('admin.roles.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                ->with('error', $e->getMessage());
        }
    }
} 