<footer>
    <p>This is footer.</p>
</footer>

<div class="modal fade" id="getMyNotifications" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр моих уведомлений</h4>
            </div>
            <div id="user_notifications" class="modal-body">
                <?php
                    echo $user_notifications;
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    function getMyNotifications() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_notifications/index",
            data: {id:<?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
            $('#user_notifications').html(message.user_notifications);
        })
    }

    function deleteUserNotification(context) {
        var id = context.getAttribute('data-id');
        var user_id = context.getAttribute('data-user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_notifications/delete_user_notification",
            data: {id: id, user_id: user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.notification_error) {
                alert(message.notification_error);
            } else {
                $('.one_notification_' + id).fadeOut(500);
            }
        })
    }

    function deleteUserNotificationsByUserId(context) {
        var user_id = context.getAttribute('data-user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_notifications/delete_user_notifications_by_user_id",
            data: {user_id: user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.notification_error) {
                alert(message.notification_error);
            } else {
                alert(message.notification_success);
                $("#getMyNotifications").trigger('click');
            }
        })
    }

    $('#showMobileMenu').click(function(){
        $('#mobileMenu').slideToggle(500);
    });

    function onLine() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/online",
            data: {id:<?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
        })
    }
    window.onload = onLine;

    function lastVisit() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/last_visit",
            data: {id:<?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
        })
    }
    window.onunload = lastVisit;

    $("#getMyNotifications").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyNotifications").modal('hide');
        };
    });
</script>