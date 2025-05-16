<?php
/**
 * Add custom meta boxes for dive site details
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add meta boxes
function sds_add_meta_boxes() {
    add_meta_box(
        'sds_dive_site_details',
        __('Dive Site Details', 'scuba-dive-sites'),
        'sds_dive_site_details_callback',
        'dive-sites',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sds_add_meta_boxes');

// Meta box callback function
function sds_dive_site_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('sds_save_meta_box_data', 'sds_meta_box_nonce');

    // Get existing values
    $max_depth = get_post_meta($post->ID, '_sds_max_depth', true);
    $min_certification = get_post_meta($post->ID, '_sds_min_certification', true);
    $water_temp = get_post_meta($post->ID, '_sds_water_temp', true);
    $visibility = get_post_meta($post->ID, '_sds_visibility', true);
    $coordinates = get_post_meta($post->ID, '_sds_coordinates', true);
    
    // Output form
    ?>
    <p>
        <label for="sds_max_depth"><?php _e('Maximum Depth (meters):', 'scuba-dive-sites'); ?></label>
        <input type="number" id="sds_max_depth" name="sds_max_depth" value="<?php echo esc_attr($max_depth); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sds_min_certification"><?php _e('Minimum Certification:', 'scuba-dive-sites'); ?></label>
        <select id="sds_min_certification" name="sds_min_certification" class="widefat">
            <option value="open-water" <?php selected($min_certification, 'open-water'); ?>><?php _e('Open Water', 'scuba-dive-sites'); ?></option>
            <option value="advanced" <?php selected($min_certification, 'advanced'); ?>><?php _e('Advanced Open Water', 'scuba-dive-sites'); ?></option>
            <option value="rescue" <?php selected($min_certification, 'rescue'); ?>><?php _e('Rescue Diver', 'scuba-dive-sites'); ?></option>
        </select>
    </p>
    
    <p>
        <label for="sds_water_temp"><?php _e('Water Temperature (°C):', 'scuba-dive-sites'); ?></label>
        <input type="number" id="sds_water_temp" name="sds_water_temp" value="<?php echo esc_attr($water_temp); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sds_visibility"><?php _e('Visibility (meters):', 'scuba-dive-sites'); ?></label>
        <input type="number" id="sds_visibility" name="sds_visibility" value="<?php echo esc_attr($visibility); ?>" class="widefat">
    </p>
    
    <p>
        <label for="sds_coordinates"><?php _e('GPS Coordinates:', 'scuba-dive-sites'); ?></label>
        <input type="text" id="sds_coordinates" name="sds_coordinates" value="<?php echo esc_attr($coordinates); ?>" class="widefat" placeholder="e.g., 25.0343° N, 77.3963° W">
    </p>
    <?php
}

// Save meta box data
function sds_save_meta_box_data($post_id) {
    // Check if our nonce is set and verify it
    if (!isset($_POST['sds_meta_box_nonce']) || !wp_verify_nonce($_POST['sds_meta_box_nonce'], 'sds_save_meta_box_data')) {
        return;
    }

    // Check if auto save or revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'dive-sites' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Update meta fields
    if (isset($_POST['sds_max_depth'])) {
        update_post_meta($post_id, '_sds_max_depth', sanitize_text_field($_POST['sds_max_depth']));
    }
    
    if (isset($_POST['sds_min_certification'])) {
        update_post_meta($post_id, '_sds_min_certification', sanitize_text_field($_POST['sds_min_certification']));
    }
    
    if (isset($_POST['sds_water_temp'])) {
        update_post_meta($post_id, '_sds_water_temp', sanitize_text_field($_POST['sds_water_temp']));
    }
    
    if (isset($_POST['sds_visibility'])) {
        update_post_meta($post_id, '_sds_visibility', sanitize_text_field($_POST['sds_visibility']));
    }
    
    if (isset($_POST['sds_coordinates'])) {
        update_post_meta($post_id, '_sds_coordinates', sanitize_text_field($_POST['sds_coordinates']));
    }
}
add_action('save_post', 'sds_save_meta_box_data');
