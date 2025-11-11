<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\Interfaces\PostServiceInterface;
use App\Services\CloneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected $postService;
    protected $cloneService;

    public function __construct(
        PostServiceInterface $postService,
        CloneService $cloneService
    ) {
        $this->postService = $postService;
        $this->cloneService = $cloneService;
    }

    /**
     * Display a listing of the posts.
     */
    public function index(Request $request)
    {
        $posts = $this->postService->getAllPosts(
            perPage: 10,
            search: $request->get('search', ''),
            sortField: $request->get('sort_field', 'created_at'),
            sortDirection: $request->get('sort_direction', 'desc')
        );
        
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $validatedData = $request->validated();
        $featuredImage = $request->hasFile('featured_image') ? $request->file('featured_image') : null;
        
        // Imposta lo status in base al pulsante cliccato
        if ($request->input('action') === 'publish') {
            $validatedData['status'] = 'published';
            // Imposta published_at se non è già impostato
            if (empty($validatedData['published_at'])) {
                $validatedData['published_at'] = now();
            }
        } else {
            $validatedData['status'] = 'draft';
        }
        
        $this->postService->createPost($validatedData, $featuredImage);

        return redirect()->route('admin.posts.index')
            ->with('success', __('admin.posts.created_successfully'));
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        
        // Imposta lo status in base al pulsante cliccato
        if ($request->input('action') === 'publish') {
            $validatedData['status'] = 'published';
            // Imposta published_at se non è già impostato
            if (empty($validatedData['published_at']) && $post->status !== 'published') {
                $validatedData['published_at'] = now();
            }
        } elseif ($request->input('action') === 'draft') {
            $validatedData['status'] = 'draft';
        }
        
        // Log dei dati per debug
        Log::info('Dati di aggiornamento post', [
            'post_id' => $post->id,
            'action' => $request->input('action'),
            'status' => $validatedData['status'] ?? null,
            'featured_image_old' => $post->featured_image,
            'featured_image_new' => $validatedData['featured_image'] ?? null
        ]);
        
        $featuredImage = $request->hasFile('featured_image') ? $request->file('featured_image') : null;
        
        $this->postService->updatePost($post->id, $validatedData, $featuredImage);

        return redirect()->route('admin.posts.index')
            ->with('success', __('admin.posts.updated_successfully'));
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->deletePost($post->id);

        return redirect()->route('admin.posts.index')
            ->with('success', __('admin.posts.deleted_successfully'));
    }

    /**
     * Duplicate a post (WordPress-like clone)
     */
    public function duplicate(Post $post)
    {
        // Check permission
        if (!auth()->user()->can('duplicate_posts')) {
            abort(403, 'Non hai il permesso di duplicare i post.');
        }

        try {
            $clonedPost = $this->cloneService->duplicatePost($post);

            return redirect()
                ->route('admin.posts.edit', $clonedPost)
                ->with('success', 'Post duplicato con successo! Stai modificando la copia.');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'Errore durante la duplicazione del post.');
        }
    }

    /**
     * Bulk actions on posts
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,unpublish,delete,trash',
            'posts' => 'required|array',
            'posts.*' => 'exists:posts,id',
        ]);

        $posts = Post::whereIn('id', $validated['posts'])->get();
        $count = 0;

        foreach ($posts as $post) {
            switch ($validated['action']) {
                case 'publish':
                    $post->update(['status' => 'published', 'published_at' => $post->published_at ?? now()]);
                    $count++;
                    break;
                case 'unpublish':
                    $post->update(['status' => 'draft']);
                    $count++;
                    break;
                case 'trash':
                    $post->delete(); // Soft delete
                    $count++;
                    break;
                case 'delete':
                    $post->forceDelete();
                    $count++;
                    break;
            }
        }

        $message = match($validated['action']) {
            'publish' => "{$count} posts published!",
            'unpublish' => "{$count} posts unpublished!",
            'trash' => "{$count} posts moved to trash!",
            'delete' => "{$count} posts deleted permanently!",
        };

        return back()->with('success', $message);
    }
} 