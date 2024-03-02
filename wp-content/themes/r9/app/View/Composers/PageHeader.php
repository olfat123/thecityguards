<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageHeader extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*page-header*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $title = get_the_title();
        $pages = r9_pages();
        $current_id = get_the_ID();
        $parent_page_id = wp_get_post_parent_id($current_id);
        // Get header background from ACF
        $page_header_image = get_field('header_background', r9_get_original_object_id($parent_page_id) ?: r9_get_original_object_id($current_id));
        $breadcrumbs = [
            [
                'title' => __('Home', 'r9'),
                'link' => home_url(),
            ],
        ];
        if (is_page()) {
            if ($parent_page_id) {
                $breadcrumbs[] = [
                    'title' => get_the_title($parent_page_id),
                    'link' => '#',
                ];
            } else {
                $breadcrumbs[] = [
                    'title' => get_the_title(),
                    'link' => '#',
                ];
            }
        } elseif (is_singular('vacancy')) {
            $breadcrumbs[] = [
                'title' => get_the_title($pages['careers']),
                'link' => get_permalink($pages['careers']),
            ];
            $title = __('Vacancy Details', 'r9');
            $page_header_image = get_field('header_background', $pages['careers']);
        } elseif (is_singular('news')) {
            $breadcrumbs[] = [
                'title' => get_the_title($pages['blog']),
                'link' => get_permalink($pages['blog']),
            ];
        }

        // If ACF field is empty, get the post thumbnail
        if (empty($page_header_image)) {
            $page_header_image = get_the_post_thumbnail_url();
        }

        // If post thumbnail is also empty, get the default from theme options
        if (empty($page_header_image)) {
            $page_header_image = r9_get_option('default_header_background');
        }
        return [
            'page_header_image' => $page_header_image,
            'page_header_title' => $title,
            'breadcrumbs' => $breadcrumbs
        ];
    }
}
