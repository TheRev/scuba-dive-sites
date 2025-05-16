<?php
/**
 * Register custom taxonomies
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register Difficulty Level taxonomy
function sds_register_taxonomies() {
    // Difficulty Level Taxonomy
    $labels = array(
        'name'              => _x('Difficulty Levels', 'taxonomy general name', 'scuba-dive-sites'),
        'singular_name'     => _x('Difficulty Level', 'taxonomy singular name', 'scuba-dive-sites'),
        'search_items'      => __('Search Difficulty Levels', 'scuba-dive-sites'),
        'all_items'         => __('All Difficulty Levels', 'scuba-dive-sites'),
        'parent_item'       => __('Parent Difficulty Level', 'scuba-dive-sites'),
        'parent_item_colon' => __('Parent Difficulty Level:', 'scuba-dive-sites'),
        'edit_item'         => __('Edit Difficulty Level', 'scuba-dive-sites'),
        'update_item'       => __('Update Difficulty Level', 'scuba-dive-sites'),
        'add_new_item'      => __('Add New Difficulty Level', 'scuba-dive-sites'),
        'new_item_name'     => __('New Difficulty Level Name', 'scuba-dive-sites'),
        'menu_name'         => __('Difficulty Levels', 'scuba-dive-sites'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'difficulty-level'),
        'show_in_rest'      => true,
    );

    register_taxonomy('difficulty-level', array('dive-sites'), $args);
    
    // Location Type Taxonomy (Shore, Boat, etc.)
    $labels = array(
        'name'              => _x('Location Types', 'taxonomy general name', 'scuba-dive-sites'),
        'singular_name'     => _x('Location Type', 'taxonomy singular name', 'scuba-dive-sites'),
        'search_items'      => __('Search Location Types', 'scuba-dive-sites'),
        'all_items'         => __('All Location Types', 'scuba-dive-sites'),
        'edit_item'         => __('Edit Location Type', 'scuba-dive-sites'),
        'update_item'       => __('Update Location Type', 'scuba-dive-sites'),
        'add_new_item'      => __('Add New Location Type', 'scuba-dive-sites'),
        'new_item_name'     => __('New Location Type Name', 'scuba-dive-sites'),
        'menu_name'         => __('Location Types', 'scuba-dive-sites'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'location-type'),
        'show_in_rest'      => true,
    );

    register_taxonomy('location-type', array('dive-sites'), $args);
    
    // Add more taxonomies as needed (Marine Life, Water Conditions, etc.)
}
add_action('init', 'sds_register_taxonomies');
