<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate and return robots.txt
     */
    public function index(): Response
    {
        $robotsTxt = $this->generateRobotsTxt();

        return response($robotsTxt, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Generate robots.txt content
     */
    protected function generateRobotsTxt(): string
    {
        $disallowAll = settings('robots_txt_disallow_all', false);

        if ($disallowAll) {
            return <<<TXT
            User-agent: *
            Disallow: /
            TXT;
        }

        $sitemapUrl = url('/sitemap.xml');

        return <<<TXT
        User-agent: *
        Disallow: /admin/
        Disallow: /api/
        Disallow: /storage/
        Disallow: /*.json$
        Disallow: /*.xml$
        Disallow: /*?*
        Allow: /

        Sitemap: {$sitemapUrl}
        TXT;
    }
}

