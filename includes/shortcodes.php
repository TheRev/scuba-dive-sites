<?php
/**
 * Register plugin shortcodes
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode to display dive sites
function sds_dive_sites_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'difficulty' => '',
            'location_type' => '',
            'limit' => 10,
            'orderby' => 'title',
            'order' => 'ASC',
        ),
        $atts,
        'dive_sites'
    );
    
    $args = array(
        'post_type' => 'dive-sites',
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );
    
    // Add taxonomy queries if specified
    $tax_query = array();
    
    if (!empty($atts['difficulty'])) {
        $tax_query[] = array(
            'taxonomy' => 'difficulty-level',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['difficulty']),
        );
    }
    
    if (!empty($atts['location_type'])) {
        $tax_query[] = array(
            'taxonomy' => 'location-type',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['location_type']),
        );
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="sds-dive-sites-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="sds-dive-site">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="sds-dive-site-thumbnail">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    <h3 class="sds-dive-site-title"><?php the_title(); ?></h3>
                </a>
                
                <div class="sds-dive-site-meta">
                    <?php 
                    $max_depth = get_post_meta(get_the_ID(), '_sds_max_depth', true);
                    if ($max_depth) {
                        echo '<span class="sds-depth">' . esc_html__('Depth: ', 'scuba-dive-sites') . esc_html($max_depth) . 'm</span>';
                    }
                    
                    $difficulty_terms = get_the_terms(get_the_ID(), 'difficulty-level');
                    if ($difficulty_terms && !is_wp_error($difficulty_terms)) {
                        echo '<span class="sds-difficulty">' . esc_html__('Difficulty: ', 'scuba-dive-sites') . esc_html($difficulty_terms[0]->name) . '</span>';
                    }
                    ?>
                </div>
                
                <div class="sds-dive-site-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            </div>
            <?php
        }
        
        echo '</div>';
    } else {
        echo '<p>' . esc_html__('No dive sites found.', 'scuba-dive-sites') . '</p>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('dive_sites', 'sds_dive_sites_shortcode');

// Shortcode for dive site filter
function sds_dive_sites_filter_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'target_id' => 'dive-sites-results',
        ),
        $atts,
        'dive_sites_filter'
    );
    
    // Get all terms for each taxonomy
    $difficulty_terms = get_terms(array(
        'taxonomy' => 'difficulty-level',
        'hide_empty' => false,
    ));
    
    $location_type_terms = get_terms(array(
        'taxonomy' => 'location-type',
        'hide_empty' => false,
    ));
    
    ob_start();
    ?>
    <div class="sds-dive-sites-filter">
        <form id="sds-filter-form">
            <div class="sds-filter-group">
                <label for="sds-difficulty"><?php _e('Difficulty Level:', 'scuba-dive-sites'); ?></label>
                <select id="sds-difficulty" name="difficulty">
                    <option value=""><?php _e('All Difficulties', 'scuba-dive-sites'); ?></option>
                    <?php foreach ($difficulty_terms as $term) : ?>
                        <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="sds-filter-group">
                <label for="sds-location-type"><?php _e('Location Type:', 'scuba-dive-sites'); ?></label>
                <select id="sds-location-type" name="location_type">
                    <option value=""><?php _e('All Location Types', 'scuba-dive-sites'); ?></option>
                    <?php foreach ($location_type_terms as $term) : ?>
                        <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="sds-filter-submit">
                <button type="submit" class="button"><?php _e('Filter Dive Sites', 'scuba-dive-sites'); ?></button>
            </div>
        </form>
        
        <div id="<?php echo esc_attr($atts['target_id']); ?>" class="sds-dive-sites-results">
            <?php echo do_shortcode('[dive_sites]'); ?>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('sds-filter-form');
        var resultsContainer = document.getElementById('<?php echo esc_js($atts['target_id']); ?>');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var difficulty = document.getElementById('sds-difficulty').value;
            var locationType = document.getElementById('sds-location-type').value;
            
            var shortcode = '[dive_sites';
            if (difficulty) {
                shortcode += ' difficulty="' + difficulty + '"';
            }
            if (locationType) {
                shortcode += ' location_type="' + locationType + '"';
            }
            shortcode += ']';
            
            // AJAX request to get filtered results
            // Note: In a real implementation, you'd use admin-ajax.php
            // This is simplified for demonstration
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    resultsContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send('action=sds_filter_dive_sites&shortcode=' + encodeURIComponent(shortcode));
        });
    });
    </script>
    <?php
    
    return ob_get_clean();
}
add_shortcode('dive_sites_filter', 'sds_dive_sites_filter_shortcode');

// AJAX handler for filtering
function sds_ajax_filter_dive_sites() {
    if (isset($_POST['shortcode'])) {
        echo do_shortcode(wp_unslash($_POST['shortcode']));
    }
    wp_die();
}
add_action('wp_ajax_sds_filter_dive_sites', 'sds_ajax_filter_dive_sites');
add_action('wp_ajax_nopriv_sds_filter_dive_sites', 'sds_ajax_filter_dive_sites');
