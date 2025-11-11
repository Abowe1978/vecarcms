<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdminService implements AdminServiceInterface
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getAllAdmins(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc')
    {
        return $this->adminRepository->getAllAdmins($perPage, $search, $sortField, $sortDirection);
    }

    public function getAdminById($id)
    {
        return $this->adminRepository->getAdminById($id);
    }

    public function createAdmin(array $data, ?UploadedFile $profileImage = null)
    {
        $adminData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ];

        if ($profileImage) {
            $adminData['profile_image'] = $this->storeProfileImage($profileImage);
        }

        $admin = $this->adminRepository->createAdmin($adminData);
        
        if (isset($data['role'])) {
            $admin->assignRole($data['role']);
        }

        return $admin;
    }

    public function updateAdmin(User $admin, array $data, ?UploadedFile $profileImage = null)
    {
        if (!$this->canEditAdmin($admin)) {
            throw new \Exception(__('admin.admins.cannot_modify_developer'));
        }
        
        $adminData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $adminData['password'] = Hash::make($data['password']);
        }

        if ($profileImage) {
            // Delete old image if exists
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }
            
            $adminData['profile_image'] = $this->storeProfileImage($profileImage);
        }
        
        $admin = $this->adminRepository->updateAdmin($admin->id, $adminData);

        // Update role if needed and if user is not a developer
        if (isset($data['role']) && !$admin->hasRole('developer')) {
            $admin->syncRoles([$data['role']]);
        }

        return $admin;
    }

    public function deleteAdmin(User $admin)
    {
        if ($admin->hasRole('developer')) {
            throw new \Exception(__('admin.admins.cannot_delete_developer'));
        }
        
        if ($admin->id === Auth::id()) {
            throw new \Exception(__('admin.admins.cannot_delete_self'));
        }
        
        // Delete profile image if exists
        if ($admin->profile_image) {
            Storage::disk('public')->delete($admin->profile_image);
        }
        
        return $this->adminRepository->deleteAdmin($admin->id);
    }

    public function getAvailableRoles()
    {
        return Role::where('name', '!=', 'developer')->get();
    }
    
    public function getAvailableRolesForEditing(User $admin)
    {
        if (Gate::allows('manage_roles')) {
            return Role::all();
        }
        
        return Role::where('name', '!=', 'developer')->get();
    }

    public function canEditAdmin(User $admin)
    {
        // Only developers can modify other developers
        if ($admin->hasRole('developer') && !Gate::allows('manage_roles')) {
            return false;
        }
        
        return true;
    }

    public function canDeleteAdmin(User $admin)
    {
        return Gate::allows('delete_admin', $admin);
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile(User $admin, array $data, ?UploadedFile $profileImage = null): void
    {
        $updateData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
        ];

        // Update password if provided
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        // Handle profile image
        if ($profileImage) {
            // Delete old profile image if exists
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }
            
            $updateData['profile_image'] = $this->storeProfileImage($profileImage);
        }

        $admin->update($updateData);
    }

    /**
     * Store a profile image and return the path
     */
    protected function storeProfileImage(UploadedFile $image): string
    {
        return $image->store('profile-images', 'public');
    }
} 