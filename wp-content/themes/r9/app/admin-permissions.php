<?php
function r9_roles()
{
    if(is_admin()){
        $capabilities = get_role('administrator')->capabilities;
        $capabilities['publish_pages'] = true;
        $capabilities['delete_pages'] = false;
        $capabilities['update_core'] = false;
        $capabilities['install_plugins'] = false;
        $capabilities['update_plugins'] = false;
        $capabilities['activate_plugins'] = false;
        $capabilities['delete_plugins'] = false;
        $capabilities['install_themes'] = false;
        $capabilities['update_themes'] = false;
        $capabilities['edit_themes'] = false;
        $capabilities['delete_themes'] = false;
        $capabilities['switch_themes'] = false;
        $capabilities['edit_plugins'] = false;
        $capabilities['list_users'] = true;
//    die("<pre>" . print_r($capabilities, true) . "</pre>");
        remove_role('site_admin');
        add_role(
            'site_admin',
            __('Admin'),
            $capabilities
        );
    }

}

r9_roles();
$user = wp_get_current_user();
function has_client_role()
{
    $user = wp_get_current_user();
    if ($user && in_array('client', (array)$user->roles)) {
        return true;
    }
    return false;
}

function has_admin_role()
{
    $user = wp_get_current_user();
    if ($user && in_array('administrator', (array)$user->roles)) {
        return true;
    }
    return false;
}

function r9_remove_toolbar_menu()
{
    if (is_admin() && !has_admin_role()) {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('updates');
        $wp_admin_bar->remove_menu('comments');
        $wp_admin_bar->remove_menu('view-store');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_right_now', 'dashboard', 'side');
        remove_meta_box('dashboard_activity', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');

    }
}

add_action('wp_before_admin_bar_render', 'r9_remove_toolbar_menu', 999);
function hide_dashboard_tabs()
{
    global $submenu;
    if (is_admin() && !has_admin_role()) {
        // menus
        remove_menu_page('edit.php');
        remove_menu_page('upload.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php?post_type=acf-field-group');
        remove_menu_page('users.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('widgets.php');
        remove_submenu_page('themes.php', 'widgets.php');
        remove_submenu_page('themes.php', 'nav-menus.php');
        remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page');

    }
}

/*
 * Hide Wordpress Version
 */
add_action('admin_menu', 'hide_dashboard_tabs', 9999);

add_action('in_admin_header', function () {
    if (is_admin() && has_client_role()) {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}, 1000);

/*
 * Hide Wordpress Version
 */
add_action('admin_menu', 'hide_dashboard_tabs');

function remove_wordpress_version()
{
    return '';
}

add_filter('the_generator', 'remove_wordpress_version');
