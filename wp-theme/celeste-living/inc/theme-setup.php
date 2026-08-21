<?php
/**
 * Theme Activation Setup
 * Automatically creates pages, navigation menu, and configures settings
 * when the theme is activated on a fresh WordPress install.
 */

add_action('after_switch_theme', 'clg_activate_theme');

function clg_activate_theme() {
    // Only run the full setup once — skip if the home page already exists
    if (get_page_by_path('home')) return;

    // ===== CREATE PAGES =====
    $pages = array(
        'home'                => 'Home',
        'about'               => 'About',
        'property-management' => 'Property Management',
        'customer-journey'    => 'How We Work',
        'compliance'          => 'Compliance',
        'contact'             => 'Contact',
        'terms'               => 'Terms of Business',
    );

    $page_ids = array();
    foreach ($pages as $slug => $title) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            $page_ids[$slug] = $existing->ID;
        } else {
            $page_ids[$slug] = wp_insert_post(array(
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'post_type'   => 'page',
            ));
        }
    }

    // ===== SET FRONT PAGE =====
    update_option('show_on_front', 'page');
    update_option('page_on_front', $page_ids['home']);

    // ===== SET PERMALINKS =====
    update_option('permalink_structure', '/%postname%/');
    flush_rewrite_rules();

    // ===== CREATE NAVIGATION MENU =====
    $menu_name   = 'Main Navigation';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        $add_item = function ($title, $page_slug, $order) use ($menu_id, $page_ids) {
            return wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title'     => $title,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page_ids[$page_slug],
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $order,
            ));
        };

        $add_item('Home', 'home', 1);
        $add_item('About', 'about', 2);
        $add_item('Property Management', 'property-management', 3);
        $add_item('How we work', 'customer-journey', 4);
        $add_item('Compliance', 'compliance', 5);
        $add_item('Contact', 'contact', 6);

        // Assign the menu to all three locations (desktop, mobile, footer)
        set_theme_mod('nav_menu_locations', array(
            'primary' => $menu_id,
            'mobile'  => $menu_id,
            'footer'  => $menu_id,
        ));
    }

    // ===== DELETE DEFAULT CONTENT =====
    $sample = get_page_by_path('sample-page');
    if ($sample) wp_delete_post($sample->ID, true);

    $hello = get_page_by_path('hello-world', OBJECT, 'post');
    if ($hello) wp_delete_post($hello->ID, true);

    // Delete the default WordPress comment
    wp_delete_comment(1, true);
}
