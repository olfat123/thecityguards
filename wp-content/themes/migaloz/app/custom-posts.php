<?php
// custom posts
function migaloz_custom_post_types()
{
    migaloz_register_post_type('news', [
        'single_label' => 'News',
        'plural_label' => 'News',
        'menu_label' => 'News',
    ]);

    migaloz_register_post_type('vacancy', [
        'editor' => false,
        'thumbnail' => false,
    ]);
    // migaloz_register_post_type('post-type');
}

add_action('init', 'migaloz_custom_post_types');

function migaloz_custom_taxonomies()
{
    // migaloz_register_taxonomy('tax-name', 'post-type', [
    //     'label' => 'label'
    // ]);
}

add_action('init', 'migaloz_custom_taxonomies');
