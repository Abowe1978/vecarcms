<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Services\Interfaces\TagServiceInterface;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagServiceInterface $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = $this->tagService->getAllTags();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $validatedData = $request->validated();
        $this->tagService->createTag($validatedData);

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.tags.success.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $validatedData = $request->validated();
        $this->tagService->updateTag($tag, $validatedData);

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.tags.success.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $this->tagService->deleteTag($tag);

        return redirect()->route('admin.tags.index')
            ->with('success', __('admin.tags.success.deleted'));
    }
}
