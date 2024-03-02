<?php

namespace App\View\Composers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Roots\Acorn\View\Composer;

class News extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*template-news*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $news = new \WP_Query([
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'fields' => 'ids',
            'cache_results' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
        return [
            'news' => $news->get_posts(),
        ];
    }
}
