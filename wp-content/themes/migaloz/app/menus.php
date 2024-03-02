<?php
register_nav_menu('header-menu', 'Header Menu');
register_nav_menu('footer-menu', 'Footer Menu');

function get_main_menu()
{
    $main_menu = [];
    if (wp_get_nav_menu_items('main-menu')) {
        $main_menu_temp = collect(wp_get_nav_menu_items('main-menu'));
        $main_menu_parents = $main_menu_temp->where('menu_item_parent', 0);
        foreach ($main_menu_parents as $parent) {
            $main_menu[] = [
                'id' => $parent->object_id,
                'title' => $parent->title,
                'url' => $parent->url,
                'children' => $main_menu_temp->where('menu_item_parent', $parent->ID)->map(function ($item) {
                    return [
                        'id' => $item->object_id,
                        'title' => $item->title,
                        'url' => $item->url,
                        'is_active' => get_the_ID() == $item->object_id,
                    ];
                })->toArray(),
            ];
        }
    }
    return $main_menu;
}