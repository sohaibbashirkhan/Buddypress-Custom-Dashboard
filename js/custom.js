jQuery(document).ready(function($) {
    // Like functionality
    $('.alike').on('click', function(e) {
        e.preventDefault();
        var activityID = $(this).data('activity-id');
        $.post(ajaxurl, {
            action: 'bp_like_activity',
            activity_id: activityID
        }, function(response) {
            // Handle like response
        });
    });

    // Share functionality
    $('.ashare').on('click', function(e) {
        e.preventDefault();
        var activityID = $(this).data('activity-id');
        // Handle share logic (e.g., show share modal)
    });
});
