<!DOCTYPE html>
<html>
<head>
    <title>Поиск друзей</title>
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
            <div id="friends" class="row">
                <?php echo $search?>
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

    function playSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.play();
    }
    function pauseSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.pause();
    }
    function getCommonFriends(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var search_user_id = context.parentElement.parentElement.parentElement.getAttribute('data-search_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>friends/common_friends",
            data: {user_id: user_id, friend_id: search_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_friends").html(message.common_friends);
        })
    }
    function getCommonBooks(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var search_user_id = context.parentElement.parentElement.parentElement.getAttribute('data-search_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_fans/common_books",
            data: {user_id: user_id, friend_id: search_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_books").html(message.common_books);
        })
    }
    function getCommonEvents(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var search_user_id = context.parentElement.parentElement.parentElement.getAttribute('data-search_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/common_events",
            data: {user_id: user_id, friend_id: search_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#common_events").html(message.common_events);
        })
    }
    function getCommonSongs(context) {
        var user_id = context.parentElement.parentElement.parentElement.getAttribute('data-user_id');
        var search_user_id = context.parentElement.parentElement.parentElement.getAttribute('data-search_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/common_songs",
            data: {user_id: user_id, friend_id: search_user_id, csrf_test_name: $(".csrf").val()},
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