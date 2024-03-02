<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Route;
use Roots\Acorn\View\Composer;

class Careers extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*template-careers*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $vacancies = new \WP_Query([
            'post_type' => 'vacancy',
            'post_status' => 'publish',
            'orderby' => 'date',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'cache_results' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
        return [
            'vacancies' => $vacancies->get_posts()
        ];
    }
}
