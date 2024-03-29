<?php

namespace App\View\Composers;

use Illuminate\Support\Str;
use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $menu = get_main_menu();

        $social_links = [];
        if ($item = get_theme_mod('facebook')) {
            $social_links[] = [
                'class' => 'fa-facebook-f',
                'title' => 'Facebook',
                'url' => $item,
            ];
        }
        if ($item = get_theme_mod('twitter')) {
            $social_links[] = [
                'class' => 'fa-twitter',
                'title' => 'Twitter',
                'url' => $item,
            ];
        }
        if ($item = get_theme_mod('linkedin')) {
            $social_links[] = [
                'class' => 'fa-linkedin-in',
                'title' => 'Linkedin',
                'url' => $item,
            ];
        }
        if ($item = get_theme_mod('instagram')) {
            $social_links[] = [
                'class' => 'fa-instagram',
                'title' => 'Instagram',
                'url' => $item,
            ];
        }
        if ($item = get_theme_mod('youtube')) {
            $social_links[] = [
                'class' => 'fa-youtube',
                'title' => 'Youtube',
                'url' => $item,
            ];
        }
        if (is_home() || is_404()) {
            $header_class = 'app-header-index';
        } else {
            $header_class = 'app-header-inner';
        }
        return [
            'siteName' => $this->siteName(),
            'menu' => $menu,
            'white_logo' => get_theme_mod('white_logo'),
            'colored_logo' => get_theme_mod('colored_logo'),
            'favicon' => get_theme_mod('favicon'),
            'social_links' => $social_links,
            'phone' => get_theme_mod('phone'),
            'address' => get_theme_mod('address'),
            'footer_about' => get_theme_mod('footer_about'),
            'header_class' => $header_class,
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }
}
