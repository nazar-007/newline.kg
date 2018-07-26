<!DOCTYPE html>
<html>
<head>
    <title>Прослушивание одной песни</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
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
        <div class="pos_one_song">
            <div class="navigation">
                <?php
                $prev = $current_id - 1;
                $next = $current_id + 1;

                if ($current_id > 1) {
                    echo "<a href='" . base_url() . "one_song/$prev'>Предыдущая песня</a>";
                }
                echo "<a class='right' href='" . base_url() . "one_song/$next'>Следующая песня</a>";

                ?>
            </div>
            <?php
            if ($song_num_rows != 1) {
                die('Песня удалена или ещё не добавлена!');
            }
            $session_user_id = $_SESSION['user_id'];
            foreach ($one_song as $info_song) {
                $song_id = $info_song->id;
                echo "<h3 class='centered'>$info_song->song_singer - $info_song->song_name</h3>
                    <div>
                        <audio class='one-player' autoplay src='" . base_url() . "uploads/song_files/$info_song->song_file' controls controlsList='nodownload'></audio>
                    </div>
                    <div class='one_song_actions'>
                        <span class='emotions_field_$song_id' data-emotioned_user_id='$session_user_id' data-song_id='$song_id'>";
                if ($emotion_num_rows == 0) {
                    echo "<img onclick='insertSongEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>";
                } else {
                    echo "<img onclick='deleteSongEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>";
                }
                echo "<span class='badge' onclick='getSongEmotions(this)' data-toggle='modal' data-target='#getSongEmotions'>$total_emotions</span>
                        </span>
                        <span class='comments_field_$song_id' data-commented_user_id='$session_user_id' data-song_id='$song_id'>
                            <span onclick='getSongComments(this)' data-toggle='modal' data-target='#getSongComments'>
                                <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_comments</span>
                            </span>
                        </span>
                        <span class='fans_field_$song_id' data-fan_user_id='$session_user_id' data-song_id='$song_id'>";
                if ($fan_num_rows == 0) {
                    echo "<img onclick='insertSongFan(this)' src='" . base_url() . "uploads/icons/not-fan.png'>";
                } else {
                    echo "<img onclick='deleteSongFan(this)' src='" . base_url(). "uploads/icons/fan.png'>";
                }
                echo "<span class='badge' onclick='getSongFans(this)' data-toggle='modal' data-target='#getSongFans'>$total_fans</span>
                        </span>
                            <span>
                                <a href='" . base_url() ."songs/download_song?id=$song_id' data-id='$song_id'>
                                    <img src='" . base_url() . "uploads/icons/download.png'>
                                </a>
                            </span>
                            <span class='complaints_field_$song_id' data-complained_user_id='$session_user_id' data-song_id='$song_id'>";
                if ($complaint_num_rows == 0) {
                    echo "<img onclick='insertSongComplaintPress(this)' data-toggle='modal' data-target='#insertSongComplaint' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
                }
                echo "</span>
                            </div>
                        <div>
                            <div>
                                <strong class='song_th'>Текст песни: </strong>
                                <span class=song_td'>
                                    <pre>
                                        $info_song->song_lyrics
                                    </pre>        
                                </span>
                            </div>
                            <div>
                                <strong class='song_th'>Категория: </strong>
                                <span class='song_td'>$info_song->category_name</span>
                            </div>
                        </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>

<div class="modal fade" id="getSongComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к песне</h4>
            </div>
            <div id="one_song_comments" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getSongEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на песню</h4>
            </div>
            <div id="one_song_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getSongFans" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, добавившие песню в любимки</h4>
            </div>
            <div id="one_song_fans" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertSongComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Отправление жалобы на песню</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertSongComplaint(this)">
                    <div class="form-group">
                        <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label for="complaint_text">Текст жалобы:</label>
                        <textarea id="complaint_text" class="form-control" name="complaint_text"></textarea>
                        <input class="song_id" type="hidden" name="song_id">
                        <input class="complained_user_id" type="hidden" name="complained_user_id">
                        <span id="complaint_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger center-block">Отправить жалобу</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    window.onblur = function () {console.log('неактивен')};
    window.onfocus = function () {console.log('снова активен')};

    function getSongComments(context) {
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_comments/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_comments").html(message.one_song_comments);
        })
    }
    function getSongEmotions(context) {
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_emotions/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_emotions").html(message.one_song_emotions);
        })
    }
    function getSongFans(context) {
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_fans").html(message.one_song_fans);
        })
    }

    function insertSongComment(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_comments/insert_song_comment",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#comment_text").val('');
            if (message.comment_text != '') {
                $(".comments_by_song").prepend("<div class='one_comment_" + message.comment_id + "'>" +
                    "<div class='commented_user'>" +
                    "<a href='<?php echo base_url()?>one_user/" + message.user_email + "'>" +
                        "<img src='<?php echo base_url()?>uploads/images/user_images/" + message.user_image + "' class='commented_avatar'>" + message.user_name + " " +
                    "</a>" +
                    "<span class='comment-date-time'>" + message.comment_date + " <br>" + message.comment_time + "</span>" +
                    "<div onclick='deleteSongComment(this)' data-song_comment_id='" + message.comment_id + "' data-song_id='" + message.song_id + "' class='right'>X</div>" +
                    "</div>" +
                    "<div class='comment_text'>" + message.comment_text + "</div> " +
                    "</div>");
                $(".comments_field_" + message.song_id).html("<span onclick='getSongComments(this)' data-toggle='modal' data-target='#getSongComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            } else {
                alert(message.comment_error);
            }
        })
    }
    function deleteSongComment(context) {
        var song_comment_id = context.getAttribute('data-song_comment_id');
        var song_id = context.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_comments/delete_song_comment",
            data: {id: song_comment_id, song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + song_comment_id).fadeOut(500);;
                $(".comments_field_" + song_id).html("<span onclick='getSongComments(this)' data-toggle='modal' data-target='#getSongComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            }
        })
    }
    function insertSongComplaintPress(context) {
        var song_id = context.parentElement.getAttribute('data-song_id');
        var complained_user_id = context.parentElement.getAttribute('data-complained_user_id');
        $('.song_id').val(song_id);
        $('.complained_user_id').val(complained_user_id);
    }

    function insertSongComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_complaints/insert_song_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#complaint_text").val('');
            if (message.complaint_num_rows == 0 && message.complaint_text != '') {
                $("#insertSongComplaint").trigger('click');
                alert(message.complaint_success);
                $(".complaints_field_" + message.song_id).html("");
            } else {
                $("#insertSongComplaint").trigger('click');
                alert(message.complaint_error);
            }
        })
    }
    function insertSongEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_emotions/insert_song_emotion",
            data: {emotioned_user_id: emotioned_user_id, song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows == 0) {
                $(".emotions_field_" + song_id).html("<img onclick='deleteSongEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getSongEmotions(this)' data-toggle='modal' data-target='#getSongEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deleteSongEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_emotions/delete_song_emotion",
            data: {emotioned_user_id: emotioned_user_id, song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".emotions_field_" + song_id).html("<img onclick='insertSongEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".emotions_field_" + book_id).html("<img onclick='insertSongEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getSongEmotions(this)' data-toggle='modal' data-target='#getSongEmotions'>" + message.total_emotions + "</span>");
                }
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function insertSongFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/insert_song_fan",
            data: {fan_user_id: fan_user_id, song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows == 0) {
                $(".fans_field_" + song_id).html("<img onclick='deleteSongFan(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/fan.png'><span class='badge' onclick='getSongFans(this)' data-toggle='modal' data-target='#getSongFans'>" + message.total_fans + "</span>");
            } else {
                alert(message.fan_error);
            }
        })
    }
    function deleteSongFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var song_id = context.parentElement.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/delete_song_fan",
            data: {fan_user_id: fan_user_id, song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows > 0) {
                if (message.total_fans == null) {
                    $(".fans_field_" + song_id).html("<img onclick='insertSongFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'>");
                } else {
                    $(".fans_field_" + song_id).html("<img onclick='insertSpngFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'><span class='badge' onclick='getSongFans(this)' data-toggle='modal' data-target='#getSongFans'>" + message.total_fans + "</span>");
                }
            } else {
                alert(message.share_error);
            }
        })
    }

    $("#getSongComments").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getSongComments").modal('hide');
        };
    });
    $("#getSongEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getSongEmotions").modal('hide');
        };
    });
    $("#getSongFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getSongFans").modal('hide');
        };
    });
    $("#insertSongComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertSongComplaint").modal('hide');
        };
    });
</script>

</body>
</html>