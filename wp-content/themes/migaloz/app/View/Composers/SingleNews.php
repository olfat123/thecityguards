<?php

namespace App\View\Composers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Roots\Acorn\View\Composer;

class SingleNews extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $date = migaloz_get_date();
        $related_news = new \WP_Query([
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => 2,
            'orderby' => 'date',
            'order' => 'DESC',
            'fields' => 'ids',
            'post__not_in' => array(get_the_ID()),
            'cache_results' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
        return [
            'related_news' => $related_news->get_posts(),
            'date' => $date,
            'url' => request()->fullUrl(),
        ];
    }
}
