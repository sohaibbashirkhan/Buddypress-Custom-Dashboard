<?php
// Custom Post Types for Admin Posts
function create_custom_post_types() {
    register_post_type('admin_post', array(
        'labels' => array(
            'name' => __('Admin Posts'),
            'singular_name' => __('Admin Post')
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'comments'),
        'capability_type' => 'post',
    ));
}
add_action('init', 'create_custom_post_types');
