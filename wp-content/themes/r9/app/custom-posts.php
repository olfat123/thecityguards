<?php
// custom posts
function r9_custom_post_types()
{
    r9_register_post_type('news', [
        'single_label' => 'News',
        'plural_label' => 'News',
        'menu_label' => 'News',
    ]);

    r9_register_post_type('vacancy', [
        'editor' => false,
        'thumbnail' => false,
    ]);
    // r9_register_post_type('post-type');
}

add_action('init', 'r9_custom_post_types');

function r9_custom_taxonomies()
{
    // r9_register_taxonomy('tax-name', 'post-type', [
    //     'label' => 'label'
    // ]);
}

add_action('init', 'r9_custom_taxonomies');
