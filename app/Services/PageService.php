<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PageService
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;
    
    /**
     * Constructor
     */
    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    
    /**
     * Get paginated pages
     *
     * @param string|null $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedPages(?string $search, string $sortField, string $sortDirection, int $perPage): LengthAwarePaginator
    {
        return $this->pageRepository->getPaginatedPages($search, $sortField, $sortDirection, $perPage);
    }
    
    /**
     * Delete a page
     *
     * @param int $pageId
     * @return bool
     */
    public function deletePage(int $pageId): bool
    {
        $page = $this->pageRepository->findById($pageId);
        
        if (!$page) {
            return false;
        }
        
        return $this->pageRepository->deletePage($page);
    }
} 