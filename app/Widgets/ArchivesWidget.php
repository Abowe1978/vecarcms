<?php

namespace App\Widgets;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ArchivesWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Archives';
    }

    public function getDescription(): string
    {
        return 'List published posts grouped by month.';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'limit',
                'label' => 'Number of months to show',
                'type' => 'number',
                'default' => 12,
                'min' => 1,
                'max' => 36,
            ],
            [
                'name' => 'show_count',
                'label' => 'Show post count',
                'type' => 'checkbox',
                'default' => true,
            ],
        ];
    }

    public function render(): string
    {
        $limit = (int) $this->getSetting('limit', 12);
        $showCount = (bool) $this->getSetting('show_count', true);

        $archives = Post::query()
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as aggregate_count')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->groupBy('year', 'month')
            ->orderBy(DB::raw('MIN(published_at)'), 'desc')
            ->limit($limit)
            ->get();

        if ($archives->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $html = '<div class="widget widget-archives">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<ul class="list-unstyled mb-0">';

        foreach ($archives as $archive) {
            $month = str_pad((string) $archive->month, 2, '0', STR_PAD_LEFT);
            $dateObj = \Carbon\Carbon::createFromDate($archive->year, $archive->month, 1);
            $label = $dateObj->format('F Y');

            $url = url('blog/' . $archive->year . '/' . $month);

            $html .= '<li class="d-flex justify-content-between align-items-center mb-2">';
            $html .= '<a href="' . e($url) . '" class="text-muted text-decoration-none">';
            $html .= e($label);
            $html .= '</a>';

            if ($showCount) {
                $html .= '<span class="badge bg-light text-dark">' . (int) $archive->aggregate_count . '</span>';
            }

            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }
}


