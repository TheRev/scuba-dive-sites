<?php
/**
 * Register custom post types
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register Dive Sites post type
function sds_register_dive_sites_post_type() {
    $labels = array(
        'name'               => _x('Dive Sites', 'post type general name', 'scuba-dive-sites'),
        'singular_name'      => _x('Dive Site', 'post type singular name', 'scuba-dive-sites'),
        'menu_name'          => _x('Dive Sites', 'admin menu', 'scuba-dive-sites'),
        'name_admin_bar'     => _x('Dive Site', 'add new on admin bar', 'scuba-dive-sites'),
        'add_new'            => _x('Add New', 'dive site', 'scuba-dive-sites'),
        'add_new_item'       => __('Add New Dive Site', 'scuba-dive-sites'),
        'new_item'           => __('New Dive Site', 'scuba-dive-sites'),
        'edit_item'          => __('Edit Dive Site', 'scuba-dive-sites'),
        'view_item'          => __('View Dive Site', 'scuba-dive-sites'),
        'all_items'          => __('All Dive Sites', 'scuba-dive-sites'),
        'search_items'       => __('Search Dive Sites', 'scuba-dive-sites'),
        'parent_item_colon'  => __('Parent Dive Sites:', 'scuba-dive-sites'),
        'not_found'          => __('No dive sites found.', 'scuba-dive-sites'),
        'not_found_in_trash' => __('No dive sites found in Trash.', 'scuba-dive-sites')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Scuba diving locations for beginners', 'scuba-dive-sites'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'dive-sites'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest'       => true,
    );

    register_post_type('dive-sites', $args);
}
add_action('init', 'sds_register_dive_sites_post_type');
