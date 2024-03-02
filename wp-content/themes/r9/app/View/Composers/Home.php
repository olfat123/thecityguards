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
        $languages = r9_get_languages();
        $current_lang = $languages['current']['code'];
        $other_lang = $languages['another']['code'];
        $slides = [];
        if (r9_get_option('home_intro_slides')) {
            foreach (r9_get_option('home_intro_slides') as $item) {
                $title = $item['title_' . $current_lang] ?: $item['title_' . $other_lang];
                $content = $item['content_' . $current_lang] ?: $item['content_' . $other_lang];
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
