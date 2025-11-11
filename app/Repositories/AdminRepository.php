<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminRepository implements AdminRepositoryInterface
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function getAllAdmins(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc')
    {
        $query = $this->user->role(['admin', 'super-admin', 'developer']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }
        
        // Assicura che il campo di ordinamento sia valido
        if (!in_array($sortField, ['name', 'email', 'role', 'last_name'])) {
            $sortField = 'name';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        // Gestione speciale per l'ordinamento per ruolo
        if ($sortField === 'role') {
            // Dobbiamo usare una join per ordinare per ruolo
            $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                 ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                 ->select('users.*')
                 ->orderBy('roles.name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        return $query->paginate($perPage)
                    ->appends([
                        'search' => $search,
                        'sort' => $sortField,
                        'direction' => $sortDirection
                    ]);
    }

    public function getAdminById($id)
    {
        return $this->user->findOrFail($id);
    }

    public function createAdmin(array $data)
    {
        $user = $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ]);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function updateAdmin($id, array $data)
    {
        $user = $this->getAdminById($id);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    public function deleteAdmin($id)
    {
        $user = $this->getAdminById($id);
        return $user->delete();
    }

    public function getAvailableRoles()
    {
        return $this->role->where('name', '!=', 'developer')
            ->where(function($query) {
                $query->where('is_hidden', false)
                      ->orWhereNull('is_hidden');
            })
            ->get();
    }
} 