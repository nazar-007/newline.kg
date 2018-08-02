<!DOCTYPE html>
<html>
<head>
    <title>Мои друзья</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/friends.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/books.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/events.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/songs.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_friends">
            <div class="row friend-row">
                <div onclick="loadFriends(this)" data-link="index" data-id="0" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 friend-column active-column">
                    Все<span class="small-hidden middle-hidden"> друзья</span> <?php if ($total_all_friends > 0) { echo '(' .  $total_all_friends . ')';}?>
                </div>
                <div onclick="loadFriends(this)" data-link="online_friends" data-id="1" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 friend-column">
                    Онлайн<span class="small-hidden middle-hidden"> друзья</span> <?php if ($total_online_friends > 0) { echo '(' .  $total_online_friends . ')';}?>
                </div>
                <div onclick="loadFriends(this)" data-link="user_invites" data-id="2" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 friend-column">
                    Заявки<span class="small-hidden middle-hidden"> в друзья</span> <?php if ($total_user_invites > 0) { echo '(' . $total_user_invites . ')';}?>
                </div>
                <div onclick="loadFriends(this)" data-link="possible_friends" data-id="3" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 friend-column">
                    Возможные<span class="small-hidden middle-hidden"> друзья</span>
                </div>
            </div>
            <div id="friends" class="row">
                <?php echo $friends?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getCommonFriends" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Общие друзья с пользователем</h4>
            </div>
            <div id="common_friends" class="modal-body row">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getCommonBooks" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Общие книги с пользователем</h4>
            </div>
            <div id="common_books" class="modal-body row">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getCommonEvents" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Общие события с пользователем</h4>
            </div>
            <div id="common_events" class="modal-body row">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getCommonSongs" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Общие песни с пользователем</h4>
            </div>
            <div id="common_songs" class="modal-body row">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php  $this->load->view("footer");?>

<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>

    $(document).on("click", ".dropdown-menu li span", function (e) { e.stopImmediatePropagation() });

    function insertFriend(context) {
        var user_id = context.parentElement.getAttribute('data-user_id');
        var invited_user_id = context.parentElement.getAttribute('data-invited_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>friends/insert_friend",
            data: {user_id: user_id, friend_id: invited_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.friend_error) {
                alert(message.friend_error);
            }

            if (message.friend_success) {
                alert(message.friend_success);
                $('.invite-' + invited_user_id).remove();
            }
        })
    }

    function deleteUserInvite(context) {
        var user_id = context.parentElement.getAttribute('data-user_id');
        var invited_user_id = context.parentElement.getAttribute('data-invited_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_invites/delete_user_invite_by_user_id",
            data: {user_id: user_id, invited_user_id: invited_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.invite_error) {
                alert(message.invite_error);
            }

            if (message.invite_success) {
                alert(message.invite_success);
                $('.invite-' + invited_user_id).fadeOut(500);
            }
        })
    }

    function loadFriends(context) {
        var id = context.getAttribute('data-id');
        var link = context.getAttribute('data-link');
        if (link == 'index' || link == 'online_friends' || link == 'user_invites' || link == 'possible_friends') {
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>friends/" + link,
                data: {load: 'load', csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $(".csrf").val(message.csrf_hash);
                if (link == 'index') {
                    $("#friends").html(message.friends);
                } else if (link == 'online_friends') {
                    $("#friends").html(message.online_friends);
                } else if (link == 'user_invites') {
                    $("#friends").html(message.user_invites);
                } else if (link == 'possible_friends') {
                    $("#friends").html(message.possible_friends);
                }
                $('.friend-column').removeClass('active-column');
                $('.friend-column').eq(id).addClass('active-column');
            })
        } else {
            alert("Не удалось загрузить запрашиваемую страницу!");
        }
    }
    function playSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.play();
    }
    function pauseSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.pause();
    }
    function getCommonFriends(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var friend_id = context.parentElement.parentElement.parentElement.getAttribute('data-friend_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>friends/common_friends",
            data: {user_id: user_id, friend_id: friend_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
        })
    }
    function getCommonBooks(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var friend_id = context.parentElement.parentElement.parentElement.getAttribute('data-friend_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_fans/common_books",
            data: {user_id: user_id, friend_id: friend_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_books").html(message.common_books);
        })
    }
    function getCommonEvents(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var friend_id = context.parentElement.parentElement.parentElement.getAttribute('data-friend_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/common_events",
            data: {user_id: user_id, friend_id: friend_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_events").html(message.common_events);
        })
    }
    function getCommonSongs(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var friend_id = context.parentElement.parentElement.parentElement.getAttribute('data-friend_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/common_songs",
            data: {user_id: user_id, friend_id: friend_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_songs").html(message.common_songs);
            var player = $('.player');
            player.on('play', function() {
                player.not(this).each(function() {
                    this.pause();
                })
            });
        })
    }

    $("#getCommonFriends").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getCommonFriends").modal('hide');
        };
    });
    $("#getCommonBooks").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getCommonBooks").modal('hide');
        };
    });
    $("#getCommonEvents").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getCommonEvents").modal('hide');
        };
    });
    $("#getCommonSongs").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getCommonSongs").modal('hide');
        };
    });
</script>

</body>
</html>