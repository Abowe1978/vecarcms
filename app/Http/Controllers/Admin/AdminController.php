<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->adminService->getAvailableRoles();
        
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $validatedData = $request->validated();
        
        $profileImage = null;
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
        }
        
        try {
            $this->adminService->createAdmin($validatedData, $profileImage);
            return redirect()->route('admin.admins.index')
                ->with('success', __('admin.admins.created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        try {
            if (!$this->adminService->canEditAdmin($admin)) {
                return redirect()->route('admin.admins.index')
                    ->with('error', __('admin.admins.cannot_edit_developer'));
            }
            
            $roles = $this->adminService->getAvailableRolesForEditing($admin);
            
            return view('admin.admins.edit', compact('admin', 'roles'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, User $admin)
    {
        try {
            if (!$this->adminService->canEditAdmin($admin)) {
                return redirect()->route('admin.admins.index')
                    ->with('error', __('admin.admins.cannot_edit_developer'));
            }

            $validatedData = $request->validated();
            
            $profileImage = null;
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
            }

            $this->adminService->updateAdmin($admin, $validatedData, $profileImage);

            return redirect()->route('admin.admins.index')
                ->with('success', __('admin.admins.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        try {
            $this->adminService->deleteAdmin($admin);
            
            return redirect()->route('admin.admins.index')
                ->with('success', __('admin.admins.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')
                ->with('error', $e->getMessage());
        }
    }
} 