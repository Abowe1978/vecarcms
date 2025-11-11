<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService
    ) {}

    /**
     * Generate and return the sitemap XML
     */
    public function index(): Response
    {
        $xml = $this->sitemapService->generate();

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate and return robots.txt
     */
    public function robots(): Response
    {
        $content = $this->sitemapService->generateRobotsTxt();

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
