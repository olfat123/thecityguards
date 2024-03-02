<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Home extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*index*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $slides = [];
        if (get_option('home_intro_slides')) {
            foreach (get_option('home_intro_slides') as $item) {
                $title = $item['title'] ?: $item['title'];
                $content = $item['content'] ?: $item['content'];
                $slides[] = array_merge($item, [
                    'title' => $title,
                    'content' => $content,
                ]);
            }
        }
        $news = new \WP_Query([
            'post_type' => 'news',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 3,
            'fields' => 'ids',
            'cache_results' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
        return [
            'slides' => $slides,
            'news' => $news->get_posts(),
        ];
    }
}
