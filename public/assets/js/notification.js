$(document).ready(function() {
    $('#mark_all').on('click', function() {
    var notificationId = $(this).data('id');
  
        $.ajax({
            url: '/extra/update_notification_status',
            method: 'POST',
            data: { id: notificationId },
            success: function(response) {
                if (response == 'success') {
                    $('#notification_count').remove();
                    $('#unread_notif').remove();
                }
            }
        });
    });
});