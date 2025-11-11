<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Http\Requests\PageStoreRequest;
use App\Http\Requests\PageUpdateRequest;
use App\Repositories\PageRepository;
use App\Services\SlugService;
use App\Services\PageTemplateService;
use App\Services\CloneService;

class PageController extends Controller
{
    /**
     * @var PageRepository
     */
    protected $pageRepository;
    
    /**
     * @var SlugService
     */
    protected $slugService;
    
    /**
     * @var PageTemplateService
     */
    protected $templateService;
    
    /**
     * @var CloneService
     */
    protected $cloneService;
    
    /**
     * Constructor
     */
    public function __construct(
        PageRepository $pageRepository,
        SlugService $slugService,
        PageTemplateService $templateService,
        CloneService $cloneService
    ) {
        $this->pageRepository = $pageRepository;
        $this->slugService = $slugService;
        $this->templateService = $templateService;
        $this->cloneService = $cloneService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentPages = $this->pageRepository->getParentPages();
        $templates = $this->templateService->getAvailableTemplates();
        
        return view('admin.pages.create', compact('parentPages', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageStoreRequest $request)
    {
        // Generate a unique slug
        $slug = $this->slugService->generateUniqueSlug(
            $request->title,
            Page::class,
            $request->slug
        );
        
        // Prepare data
        $data = $request->validated();
        $data['slug'] = $slug;
        $data['is_published'] = $request->boolean('is_published');
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['is_blog'] = $request->boolean('is_blog');
        $data['show_title'] = $request->boolean('show_title', true);
        
        // Auto-set template to 'full-width' when page builder is enabled
        if ($request->has('use_page_builder') && $request->use_page_builder) {
            $data['template'] = 'full-width';
        }
        
        // Create page
        $this->pageRepository->createPage($data);

        return redirect()->route('admin.pages.index')
            ->with('success', __('admin.pages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $parentPages = $this->pageRepository->getPotentialParentPages($page);
        $templates = $this->templateService->getAvailableTemplates();
        
        return view('admin.pages.edit', compact('page', 'parentPages', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageUpdateRequest $request, Page $page)
    {
        // Generate a unique slug
        $slug = $this->slugService->generateUniqueSlug(
            $request->title,
            Page::class,
            $request->slug,
            $page->id
        );
        
        // Prepare data
        $data = $request->validated();
        $data['slug'] = $slug;
        $data['is_published'] = $request->boolean('is_published');
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['is_blog'] = $request->boolean('is_blog');
        $data['show_title'] = $request->boolean('show_title', true);
        
        // Auto-set template to 'full-width' when page builder is enabled
        if ($request->has('use_page_builder') && $request->use_page_builder) {
            $data['template'] = 'full-width';
        }
        
        // Update page
        $this->pageRepository->updatePage($page, $data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', __('admin.pages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $this->pageRepository->deletePage($page);

        return redirect()->route('admin.pages.index')
            ->with('success', __('admin.pages.deleted_successfully'));
    }

    /**
     * Duplicate a page (WordPress-like clone)
     */
    public function duplicate(Page $page)
    {
        // Check permission
        if (!auth()->user()->can('duplicate_pages')) {
            abort(403, 'Non hai il permesso di duplicare le pagine.');
        }

        try {
            $clonedPage = $this->cloneService->duplicatePage($page);

            return redirect()
                ->route('admin.pages.edit', $clonedPage)
                ->with('success', 'Pagina duplicata con successo! Stai modificando la copia.');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.pages.index')
                ->with('error', 'Errore durante la duplicazione della pagina.');
        }
    }

    /**
     * Bulk actions on pages
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'pages' => 'required|array',
            'pages.*' => 'exists:pages,id',
        ]);

        $pages = Page::whereIn('id', $validated['pages'])->get();
        $count = 0;

        foreach ($pages as $page) {
            // Prevent deleting homepage or blog page
            if (in_array($validated['action'], ['delete']) && ($page->is_homepage || $page->is_blog)) {
                continue;
            }

            switch ($validated['action']) {
                case 'publish':
                    $page->update(['is_published' => true]);
                    $count++;
                    break;
                case 'unpublish':
                    $page->update(['is_published' => false]);
                    $count++;
                    break;
                case 'delete':
                    $page->delete();
                    $count++;
                    break;
            }
        }

        $message = match($validated['action']) {
            'publish' => "{$count} pages published!",
            'unpublish' => "{$count} pages unpublished!",
            'delete' => "{$count} pages deleted!",
        };

        return back()->with('success', $message);
    }
}
