<?php
/*
Plugin Name: BuddyPress Custom Dashboard
Description: Custom dashboard for BuddyPress with Facebook-like design and category-based post visibility.
Version: 1.0
Author: Sohaib Khan
*/

// Ensure no direct access
if (!defined('ABSPATH')) exit;

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'includes/custom-posts.php');
include_once(plugin_dir_path(__FILE__) . 'includes/custom-dashboard.php');
include_once(plugin_dir_path(__FILE__) . 'includes/custom-activity.php');

// Activation hook
function custom_dashboard_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_dashboard_data';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        purchased_categories text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'custom_dashboard_activate');

// Deactivation hook
function custom_dashboard_deactivate() {
    // Deactivation code here
}
register_deactivation_hook(__FILE__, 'custom_dashboard_deactivate');

// Redirect to custom dashboard after login
function custom_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('customer', $user->roles)) {
            return home_url('/custom-dashboard/');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// Enqueue custom styles and scripts
function custom_enqueue_assets() {
    wp_enqueue_style('custom-styles', plugin_dir_url(__FILE__) . 'css/custom-styles.css');
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_assets');

// Display login form after purchase
function display_login_form_after_purchase($order_id) {
    echo '<h2>Login to your account</h2>';
    wp_login_form();
}
add_action('woocommerce_thankyou', 'display_login_form_after_purchase');

// Shortcode to display custom dashboard
function custom_dashboard_shortcode() {
    ob_start();
    display_custom_dashboard();
    return ob_get_clean();
}
add_shortcode('custom_dashboard', 'custom_dashboard_shortcode');
