<?php

namespace App\Livewire;

use App\Services\PageService;
use Livewire\Component;
use Livewire\WithPagination;

class PageTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'order';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'tailwind';
    
    /**
     * @var PageService
     */
    protected $pageService;
    
    /**
     * Constructor with dependency injection
     */
    public function boot(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function mount()
    {
        $this->search = request()->query('search', '');
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

    public function render()
    {
        $pages = $this->pageService->getPaginatedPages(
            $this->search,
            $this->sortField, 
            $this->sortDirection, 
            $this->perPage
        );

        return view('livewire.page-table', [
            'pages' => $pages
        ]);
    }

    public function delete($pageId)
    {
        try {
            if ($this->pageService->deletePage($pageId)) {
                session()->flash('success', 'Pagina eliminata con successo!');
                $this->dispatch('page-deleted'); // Trigger refresh
            } else {
                session()->flash('error', 'Impossibile eliminare la pagina.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Errore: ' . $e->getMessage());
        }
    }
}
