<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventCategory;
use App\Services\Interfaces\EventServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

class EventTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $categoryFilter = 'all';
    public $perPage = 10;
    public $sortField = 'start_date';
    public $sortDirection = 'desc';

    protected EventServiceInterface $eventService;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
        'sortField' => ['except' => 'start_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'eventUpdated' => '$refresh',
        'eventCreated' => '$refresh',
        'eventDeleted' => '$refresh',
    ];

    public function __construct()
    {
        $this->eventService = app(EventServiceInterface::class);
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function deleteEvent($eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            $this->eventService->deleteEvent($event);
            session()->flash('success', __('admin.events.success.deleted'));
            $this->dispatch('eventDeleted');
        } catch (\Exception $e) {
            session()->flash('error', __('admin.common.something_went_wrong'));
        }
    }

    public function duplicateEvent($eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            $this->eventService->duplicateEvent($event);
            session()->flash('success', __('admin.events.success.duplicated'));
            $this->dispatch('eventCreated');
        } catch (\Exception $e) {
            session()->flash('error', __('admin.common.something_went_wrong'));
        }
    }

    public function toggleStatus($eventId, $field)
    {
        try {
            $event = Event::findOrFail($eventId);
            $event->update([$field => !$event->$field]);
            $this->dispatch('eventUpdated');
        } catch (\Exception $e) {
            session()->flash('error', __('admin.common.something_went_wrong'));
        }
    }

    public function render()
    {
        $query = Event::with(['category', 'subcategory', 'eventTags', 'creator'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('location_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->categoryFilter !== 'all', function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $events = $query->paginate($this->perPage);
        $categories = EventCategory::active()->orderBy('name')->get();

        return view('livewire.admin.event-table', [
            'events' => $events,
            'categories' => $categories,
        ]);
    }
}
