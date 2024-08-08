<?php
// Display custom dashboard
function display_custom_dashboard() {
    if (is_user_logged_in()) {
        echo '<h2>Welcome to your custom dashboard!</h2>';
        echo '<form id="custom-post-form" enctype="multipart/form-data" method="post">';
        echo '<textarea name="custom_post_content" placeholder="Write something..."></textarea>';
        echo '<input type="file" name="custom_post_media" />';
        echo '<select name="custom_post_category">';
        for ($i = 1; $i <= 10; $i++) {
            echo '<option value="Category ' . $i . '">Category ' . $i . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" value="Post" />';
        echo '</form>';
    } else {
        wp_login_form();
    }
}

// Handle custom post submission
function handle_custom_post_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_user_logged_in() && !empty($_POST['custom_post_content'])) {
        $content = sanitize_text_field($_POST['custom_post_content']);
        $category = sanitize_text_field($_POST['custom_post_category']);
        $user_id = get_current_user_id();

        $post_data = array(
            'post_title'   => 'Custom Post',
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_author'  => $user_id,
            'post_type'    => 'admin_post',
            'meta_input'   => array('custom_post_category' => $category)
        );

        if (!empty($_FILES['custom_post_media']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploadedfile = $_FILES['custom_post_media'];
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $post_data['post_content'] .= '<br /><img src="' . $movefile['url'] . '" />';
            }
        }

        $post_id = wp_insert_post($post_data);
        if ($post_id) {
            bp_activity_add(array(
                'user_id'           => $user_id,
                'type'              => 'admin_post',
                'action'            => sprintf(__('%s posted an update', 'buddypress'), bp_core_get_userlink($user_id)),
                'content'           => $content,
                'primary_link'      => get_permalink($post_id),
                'secondary_item_id' => $post_id,
            ));
        }
    }
}
add_action('template_redirect', 'handle_custom_post_submission');
