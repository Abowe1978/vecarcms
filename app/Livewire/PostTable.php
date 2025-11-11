<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostService;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PostTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 5;
    public $confirmingPostDeletion = false;
    public $postToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = ['delete', 'postUpdated' => '$refresh', 'confirmDelete'];

    protected PostServiceInterface $postService;

    public function boot(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($postId)
    {
        $this->postToDelete = $postId;
        $this->dispatch('showDeleteConfirm');
    }

    public function delete($postId)
    {
        $this->postService->deletePost($postId);
        session()->flash('message', 'Post successfully deleted.');
        $this->dispatch('postDeleted');
    }

    public function confirmPostDeletion($postId)
    {
        $this->confirmingPostDeletion = true;
        $this->postToDelete = $postId;
    }

    public function deletePost()
    {
        if ($this->postToDelete) {
            $this->postService->deletePost($this->postToDelete);
            $this->confirmingPostDeletion = false;
            $this->postToDelete = null;
            session()->flash('message', 'Post successfully deleted.');
        }
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

    public function render(): View
    {
        $posts = $this->postService->getPaginatedPosts(
            $this->search, 
            $this->perPage, 
            $this->sortField, 
            $this->sortDirection
        );

        return view('livewire.post-table', [
            'posts' => $posts,
        ]);
    }
} 