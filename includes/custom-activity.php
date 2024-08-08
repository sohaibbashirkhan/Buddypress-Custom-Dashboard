<?php
// Function to add custom activity type
function custom_activity_types($activity_types) {
    $activity_types['admin_post'] = __('Admin Post', 'buddypress');
    return $activity_types;
}
add_filter('bp_activity_get_types', 'custom_activity_types');

// Function to filter activity by custom type and category
function custom_activity_query($retval) {
    if (bp_is_activity_directory() && is_user_logged_in()) {
        $user_id = get_current_user_id();
        $purchased_categories = get_user_meta($user_id, 'purchased_categories', true);

        if (!empty($purchased_categories)) {
            $purchased_categories = explode(',', $purchased_categories);
            $retval['meta_query'] = array(
                array(
                    'key'     => 'custom_post_category',
                    'value'   => $purchased_categories,
                    'compare' => 'IN',
                ),
            );
        }
    }
    return $retval;
}
add_filter('bp_after_has_activities_parse_args', 'custom_activity_query');

// Function to display admin posts in activity loop
function display_admin_posts_in_activity_loop($content, $activity) {
    if ('admin_post' === $activity->type) {
        $post_id = $activity->secondary_item_id;
        $post = get_post($post_id);

        if ($post) {
            $content = '<div class="activity-content">' . $post->post_content . '</div>';
        }
    }
    return $content;
}
add_filter('bp_get_activity_content_body', 'display_admin_posts_in_activity_loop', 10, 2);

// Function to handle like, share, and comment actions
function handle_like_comment_actions() {
    // Add your AJAX handlers and other logic here for like, comment, and share functionalities
}
add_action('wp_ajax_bp_like_activity', 'handle_like_comment_actions');
add_action('wp_ajax_nopriv_bp_like_activity', 'handle_like_comment_actions');
add_action('wp_ajax_bp_comment_activity', 'handle_like_comment_actions');
add_action('wp_ajax_nopriv_bp_comment_activity', 'handle_like_comment_actions');
