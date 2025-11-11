<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Interfaces\AdminServiceInterface;
use Livewire\Attributes\On;

class AdminTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];
    
    public function mount()
    {
        // Emit event to sync search field on page load
        $this->dispatch('searchUpdated', $this->search);
    }
    
    #[On('searchAdmins')]
    public function updateSearch($searchTerm)
    {
        $this->search = $searchTerm;
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
        // Emit event to sync search field when Livewire updates the search
        $this->dispatch('searchUpdated', $this->search);
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    public function render(AdminServiceInterface $adminService)
    {
        $admins = $adminService->getAllAdmins(
            perPage: $this->perPage,
            search: $this->search,
            sortField: $this->sortField,
            sortDirection: $this->sortDirection
        );
        
        return view('livewire.admin-table', [
            'admins' => $admins
        ]);
    }
}
