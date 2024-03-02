<?php

use Illuminate\Support\Str;

function r9_register_post_type($post_type, $args = [])
{
    $single_label = $args['single_label'] ?? Str::title(str_replace('_', ' ', $post_type));
    $plural_label = $args['plural_label'] ?? Str::plural($single_label);
    $defaults = [
        'editor' => true,
        'thumbnail' => true,
        'single_label' => $single_label,
        'plural_label' => $plural_label,
        'menu_label' => $plural_label,
        'icon' => 'dashicons-editor-ol-rtl'
    ];
    $args = array_merge($defaults, $args);
    $supports = ['title'];
    if ($args['editor']) {
        $supports[] = 'editor';
    }
    if ($args['thumbnail']) {
        $supports[] = 'thumbnail';
    }
    register_post_type($post_type, [
        'supports' => $supports,
        'public' => true,
        'labels' => [
            'name' => $args['plural_label'],
            'add_new' => 'Add New ' . $args['single_label'],
            'add_new_item' => 'Add New ' . $args['single_label'],
            'edit_item' => 'Edit ' . $args['single_label'],
            'all_items' => 'All ' . $args['plural_label'],
            'singular_name' => $args['single_label'],
            'menu_name' => $args['menu_label'], // This is the label used in the admin menu
        ],
        'menu_icon' => $args['icon'],
        'has_archive' => false,
    ]);
}

function r9_register_taxonomy($taxonomy, $post_type, $args = [])
{
    $defaults = [
        'label' => Str::title(str_replace('_', ' ', $taxonomy)),
        'rewrite' => array('slug' => Str::slug($taxonomy)),
        'hierarchical' => false,
        'show_admin_column' => false,
        'public' => true,
        'show_ui' => true,
    ];
    $args = array_merge($defaults, $args);
    register_taxonomy(
        $taxonomy,
        $post_type,
        $args
    );
}

function r9_add_panel($id, $title, $priority = null)
{
    new \Kirki\Panel(
        $id,
        [
            'title' => strtoupper($title),
            'priority' => $priority
        ]
    );
}

function r9_add_section($id, $title, $panel_id = null, $priority = 1)
{
    new \Kirki\Section(
        $id,
        [
            'title' => strtoupper($title),
            'panel' => $panel_id,
            'priority' => $priority,
        ]
    );
}

/*
 * https://docs.themeum.com/kirki/controls/
 */
function r9_add_field($type, $id, $label, $section, $args = [])
{
    $defaults = [
        'fields' => [],
        'row_label' => 'item',
        'description' => '',
        'choices' => [],
        'multi_language' => false,
    ];

    $args = array_merge($defaults, $args);
    $config = [
        'settings' => $id,
        'label' => $label,
        'description' => $args['description'],
        'section' => $section,
    ];

    switch ($type) {
        case 'text':
            $class = '\Kirki\Field\Text';
            break;
        case 'textarea':
            $class = '\Kirki\Field\Textarea';
            break;
        case 'editor':
            $class = '\Kirki\Field\Editor';
            break;
        case 'image':
            $class = '\Kirki\Field\Upload';
            break;
        case 'date':
            $class = '\Kirki\Field\Date';
            break;
        case 'repeater':
            $class = '\Kirki\Field\Repeater';
            $config = array_merge($config, [
                'row_label' => [
                    'value' => $args['row_label'],
                ],
                'fields' => $args['fields'],
            ]);
            break;
        case 'select':
            $class = '\Kirki\Field\Select';
            $config = array_merge($config, [
                'choices' => $args['choices'],
            ]);
            break;
    }
    $config_list = [];
    if ($args['multi_language']) {
        $config_en = $config_ar = $config;
        $config_en['settings'] = $config_en['settings'] . '_en';
        $config_ar['settings'] = $config_ar['settings'] . '_ar';
        $config_en['label'] = $config_en['label'] . ' [English]';
        $config_ar['label'] = $config_ar['label'] . ' [Arabic]';
        $config_list = [$config_en, $config_ar];
    } else {
        $config_list = [$config];
    }
    foreach ($config_list as $item) {
        new $class($item);
    }
}

// Customize register actions should only be used for removing default panels, etc.
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    remove_default_panels($wp_customize);
});
/**
 * Remove Default Panels And Sections
 */
function remove_default_panels($WP_Customize_Manager)
{
    $WP_Customize_Manager->remove_section('title_tagline');
    $WP_Customize_Manager->remove_section('colors');
    $WP_Customize_Manager->remove_section('header_image');
    $WP_Customize_Manager->remove_section('background_image');
    $WP_Customize_Manager->remove_section('static_front_page');
    $WP_Customize_Manager->remove_section('custom_css');
    $WP_Customize_Manager->remove_panel('woocommerce');

    if (isset($WP_Customize_Manager->widgets) && is_object($WP_Customize_Manager->widgets)) {
        //Remove all the filters/actions resiterd in WP_Customize_widgets __construct
        remove_filter('customize_refresh_nonces', array($WP_Customize_Manager->widgets, 'filter_nonces'));
        remove_action('wp_ajax_load-available-menu-items-customizer', array($WP_Customize_Manager->widgets, 'ajax_load_available_items'));
        remove_action('wp_ajax_search-available-menu-items-customizer', array($WP_Customize_Manager->widgets, 'ajax_search_available_items'));
        remove_action('customize_controls_enqueue_scripts', array($WP_Customize_Manager->widgets, 'enqueue_scripts'));
        remove_action('customize_register', array($WP_Customize_Manager->widgets, 'customize_register'), 11);
        remove_filter('customize_dynamic_setting_args', array($WP_Customize_Manager->widgets, 'filter_dynamic_setting_args'), 10, 2);
        remove_filter('customize_dynamic_setting_class', array($WP_Customize_Manager->widgets, 'filter_dynamic_setting_class'), 10, 3);
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->widgets, 'print_templates'));
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->widgets, 'available_items_template'));
        remove_action('customize_preview_init', array($WP_Customize_Manager->widgets, 'customize_preview_init'));
        remove_filter('customize_dynamic_partial_args', array($WP_Customize_Manager->widgets, 'customize_dynamic_partial_args'), 10, 2);
    }

    if (isset($WP_Customize_Manager->nav_menus) && is_object($WP_Customize_Manager->nav_menus)) {
        //Remove all the filters/actions resiterd in WP_Customize_Nav_Menus __construct
        remove_filter('customize_refresh_nonces', array($WP_Customize_Manager->nav_menus, 'filter_nonces'));
        remove_action('wp_ajax_load-available-menu-items-customizer', array($WP_Customize_Manager->nav_menus, 'ajax_load_available_items'));
        remove_action('wp_ajax_search-available-menu-items-customizer', array($WP_Customize_Manager->nav_menus, 'ajax_search_available_items'));
        remove_action('customize_controls_enqueue_scripts', array($WP_Customize_Manager->nav_menus, 'enqueue_scripts'));
        remove_action('customize_register', array($WP_Customize_Manager->nav_menus, 'customize_register'), 11);
        remove_filter('customize_dynamic_setting_args', array($WP_Customize_Manager->nav_menus, 'filter_dynamic_setting_args'), 10, 2);
        remove_filter('customize_dynamic_setting_class', array($WP_Customize_Manager->nav_menus, 'filter_dynamic_setting_class'), 10, 3);
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->nav_menus, 'print_templates'));
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->nav_menus, 'available_items_template'));
        remove_action('customize_preview_init', array($WP_Customize_Manager->nav_menus, 'customize_preview_init'));
        remove_filter('customize_dynamic_partial_args', array($WP_Customize_Manager->nav_menus, 'customize_dynamic_partial_args'), 10, 2);
    }
}

function customize_fullscreen()
{
    echo '<style>
        .wp-full-overlay.expanded {
            margin-left: 500px;;
        }
        .wp-full-overlay-sidebar {
            width: 500px;
            left: 0;
            right: 0;
        }
        .wp-full-overlay-footer{
        display:none;
        }
    </style>';
}

add_action('customize_controls_print_styles', 'customize_fullscreen');

function my_remove_meta_boxes()
{
    remove_meta_box('tagsdiv-vacancy_department', 'vacancy', 'side');
    remove_meta_box('tagsdiv-vacancy_location', 'vacancy', 'side');
    remove_meta_box('tagsdiv-product_category', 'product', 'side');
}

add_action('admin_menu', 'my_remove_meta_boxes');
