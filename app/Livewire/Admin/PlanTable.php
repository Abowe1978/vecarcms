<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\UserPlan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Interfaces\PlanServiceInterface;

class PlanTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deleteConfirmed' => 'deletePlan'
    ];

    public function mount()
    {
        // No need to inject services in mount for Livewire
    }

    public function updatingSearch()
    {
        $this->resetPage();
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

    public function deletePlan(\App\Models\Plan $plan)
    {
        $planService = app(PlanServiceInterface::class);
        
        if ($planService->countMemberships($plan) > 0) {
            session()->flash('error', __('admin.plans.error_deleting'));
            return;
        }
        $planService->deletePlan($plan);
        session()->flash('success', __('admin.plans.deleted_successfully'));
    }

    public function render()
    {
        $planService = app(PlanServiceInterface::class);
        
        $filters = [
            'search' => $this->search,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'per_page' => $this->perPage,
        ];
        $plans = $planService->getPaginatedPlans($this->perPage, $filters);
        return view('livewire.admin.plan-table', [
            'plans' => $plans
        ]);
    }
}
