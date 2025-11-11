<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\ModeratorRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ModeratorRepository implements ModeratorRepositoryInterface
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function getAllModerators(int $perPage = 10, ?string $search = null, string $sortField = 'name', string $sortDirection = 'asc')
    {
        $query = $this->user->role(['moderator']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }
        
        // Assicura che il campo di ordinamento sia valido
        if (!in_array($sortField, ['name', 'email', 'last_name'])) {
            $sortField = 'name';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        return $query->orderBy($sortField, $sortDirection)->paginate($perPage);
    }

    public function getModeratorById($id)
    {
        return $this->user->findOrFail($id);
    }

    public function createModerator(array $data)
    {
        $moderator = $this->user->create($data);
        $moderator->assignRole('moderator');
        
        return $moderator;
    }

    public function updateModerator($id, array $data)
    {
        $moderator = $this->getModeratorById($id);
        $moderator->update($data);
        
        return $moderator;
    }

    public function deleteModerator($id)
    {
        $moderator = $this->getModeratorById($id);
        
        return $moderator->delete();
    }
} 