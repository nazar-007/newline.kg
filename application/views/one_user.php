<!DOCTYPE html>
<html>
<head>
    <title>Страница пользователя</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/publications.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/books.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/events.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/songs.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/gift_stakes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/users.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_my_page">
            <div class="row" id="user_page">
                <?php
                if ($user_num_rows != 1) {
                    die('Страница удалена или ещё не создана!');
                }
                ?>

                <?php foreach ($users as $user):?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 about">
                    <img class="img-thumbnail" src="<?php echo base_url()?>uploads/images/user_images/<?php echo $user->main_image?>">
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 about">
                    <h2 class="centered"><?php echo $user->nickname . ' ' . $user->surname?></h2>
                    <div id="showMobileInfo" class="middle-hidden big-hidden huge-hidden">Показать информацию</div>
                    <div id="mobileInfo" class="small-hidden">
                        <div>
                            <strong class='info_th'>Дата рождения: </strong>
                            <span class='info_td'><?php echo $user->birth_date . " " . $user->birth_year;?></span>
                        </div>
                        <div>
                            <strong class='info_th'>Страна: </strong>
                            <span class='info_td'><?php echo $user->home_land;?></span>
                        </div>
                        <div>
                            <strong class='info_th'>Школы: </strong>
                            <span class='info_td'><?php echo $user->education_schools?></span>
                        </div>
                        <div>
                            <strong class='info_th'>Университеты: </strong>
                            <span class='info_td'><?php echo $user->education_universities?></span>
                        </div>
                        <div>
                            <strong class='info_th'>Семейное положение: </strong>
                            <span class='info_td'><?php echo $user->family_position?></span>
                        </div>
                        <div>
                            <strong class='info_th'>Звание: </strong>
                            <span class='info_td'><?php echo $user->rank . ' (' . $user->rating . ')'?></span>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 centered">
                    <span class="user_page_emotions" data-user_id="<?php echo $user_id;?>" data-emotioned_user_id="<?php echo $_SESSION['user_id']?>">
                    <?php if ($user_page_emotion_num_rows > 0) {
                        echo "<img onclick='deleteUserPageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                    } else {
                        echo "<img onclick='insertUserPageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                    }
                    ?>
                        <span data-toggle="modal" data-target="#getUserPageEmotions" onclick="getUserPageEmotions(this)" class="badge"><?php echo $total_user_page_emotions?></span>
                    </span>
                    <button class="btn btn-default">
                        <a href='<?php echo base_url()?>update'>
                            <img src="<?php echo base_url()?>uploads/icons/update.png">
                            <?php echo $user_page_emotion_num_rows?>
                        </a>
                    </button>
                    <img id="showMobileInterests" class="middle-hidden big-hidden huge-hidden" src="/uploads/icons/world.png">
                    <div id="mobileInterests" class="small-hidden interests">
                        <div class="one-interest">
                            <button class="btn btn-primary btn-interests">
                                <a class="white" href="<?php echo base_url()?>friends">Друзья <?php if ($total_friends > 0) { echo '(' .  $total_friends . ')';}?></a>
                            </button>
                        </div>
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#getUserBooks" class="btn btn-primary btn-interests">Книги <?php if ($total_books > 0) { echo '(' .  $total_books . ')';}?></button>
                        </div>
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#getUserEvents" class="btn btn-primary btn-interests">События <?php if ($total_events > 0) { echo '(' .  $total_events . ')';}?></button>
                        </div>
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#getUserSongs" class="btn btn-primary btn-interests">Песни <?php if ($total_songs > 0) { echo '(' .  $total_songs . ')';}?></button>
                        </div>
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#getUserGifts" class="btn btn-primary btn-interests">Подарки</button>
                        </div>
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#getUserStakes" class="btn btn-primary btn-interests">Награды</button>
                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <div class="row" data-user_id="<?php echo $user_id; ?>">
                        <div onclick="loadBlock(this)" data-link="publications/user_publications" data-id="0" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 user-column active-column">
                            Публикации
                        </div>
                        <div onclick="loadBlock(this)" data-link="guest_messages/index" data-id="1" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 user-column">
                            Гостевая
                        </div>
                        <div onclick="loadBlock(this)" data-link="publication_shares/user_publication_shares" data-id="2" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 user-column">
                            Репосты
                        </div>
                    </div>
                </div>
                <div id="publications" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserBooks" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Книги пользователя</h4>
            </div>
            <div id="get_user_books" class="modal-body row">
                <?php
                if (count($user_books) == 0) {
                    echo "<h4 class='centered'>Пока пользователь не добавил ни одной книги.</h4>";
                } else {
                    foreach ($user_books as $user_book) {
                        echo "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$user_book->book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$user_book->book_image'>
                        </div>
                        <div class='book_name'>$user_book->book_name</div>
                    </a>
                </div>";
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserEvents" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">События пользователя</h4>
            </div>
            <div id="get_user_events" class="modal-body row">
                <?php
                if (count($user_events) == 0) {
                    echo "<h4 class='centered'>Пока пользователь не добавил ни одного события.</h4>";
                } else {
                    foreach ($user_events as $user_event) {
                        $event_id = $user_event->event_id;
                        $event_name = $user_event->event_name;
                        $event_date = $user_event->event_start_date;
                        $day = $event_date[0] . $event_date[1];
                        $year = $event_date[6] . $event_date[7] . $event_date[8] . $event_date[9];
                        if ($event_date[3] == '0') {
                            $month_index = $event_date[4];
                        } else {
                            $month_index = $event_date[3] . $event_date[4];
                        }
                        $months_array = array(
                            "1" => "Января", "2" => "Февраля", "3" => "Марта",
                            "4" => "Апреля", "5" => "Мая", "6" => "Июня",
                            "7" => "Июля", "8" => "Августа", "9" => "Сентября",
                            "10" => "Октября", "11" => "Ноября", "12" => "Декабря"
                        );
                        $month = $months_array[$month_index];
                        echo "<div class='list col-xs-6 col-sm-6 col-md-6 col-lg-6 event'>
                        <div class='centered'>
                        <div class='event-date'>
                        <a href='" . base_url() . "one_event/$event_id'>
                            <div class='date'>
                                $day
                            </div>
                            <br>
                            <div class='date'>
                                $month
                            </div>
                            <br>
                            <div class='date'>
                                $year
                            </div>
                        </a>
                        </div>
                        <div class='event-name'>
                            $event_name
                        </div>
                    </div>
                    </div>";
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserSongs" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Песни пользователя</h4>
            </div>
            <div id="get_user_songs" class="modal-body">
                <?php

                if (count($user_songs) == 0) {
                    echo "<h4 class='centered'>Пока пользователь не добавил ни одной песни.</h4>";
                } else {
                    foreach ($user_songs as $user_song) {
                        echo "<div><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png'>
                        </span>
                    </div></div>
                    <div class='song-name'>
                        <a href='" . base_url() . "one_song/$user_song->song_id'>
                            $user_song->song_singer - $user_song->song_name
                        </a>
                    </div>
                    <audio class='player' src='" . base_url() . "uploads/song_files/$user_song->song_file' controls controlsList='nodownload'></audio>";
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserGifts" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Подарки пользователя</h4>
            </div>
            <div id="get_user_events" class="modal-body">
                <div class="all_gifts">
                    <?php

                    if (count($user_gifts) == 0) {
                        echo "<div class='centered'>Пользователю ещё никто ничего не подарил!</div>";
                    } else {
                        foreach ($user_gifts as $user_gift) {
                            echo "<div class='centered one_my_gift_$user_gift->id my_gift'>
                        <div class='gift_date'>
                            $user_gift->sent_date, $user_gift->sent_time 
                        </div>
                        <img src='" . base_url() . "uploads/images/gift_images/$user_gift->gift_file'>
                        <div class='sent_gift_name'>
                            $user_gift->gift_name
                        </div>
                        <div class='sent_user_name'>
                            от <a href='" . base_url() . "one_user/$user_gift->email'>$user_gift->nickname $user_gift->surname</a>
                        </div>
                    </div>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserStakes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Награды пользователя</h4>
            </div>
            <div id="get_user_stakes" class="modal-body">
                <?php
                if (count($user_stakes) == 0) {
                    echo "<div class='centered'>Пользователь не получил ни одной награды!</div>";
                } else {
                    foreach ($user_stakes as $user_stake) {
                        echo "<div class='centered one_my_stake_$user_stake->id my_stake'>
                        <div class='stake_date'>
                            $user_stake->stake_date, $user_stake->stake_time 
                        </div>
                        <img src='" . base_url() . "uploads/images/stake_images/$user_stake->stake_file'>
                        <div class='sent_gift_name'>
                            $user_stake->stake_name
                        </div>
                    </div>";
                    }
                }
                ?>
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getPublicationComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к публикации пользователя</h4>
            </div>
            <div id="one_publication_comments" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getPublicationEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на публикацию пользователя</h4>
            </div>
            <div id="one_publication_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getPublicationImageEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоцию на фотку публикации пользователя</h4>
            </div>
            <div id="one_publication_image_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getPublicationShares" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поделившиеся публикацией пользователя</h4>
            </div>
            <div id="one_publication_shares" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getUserPageEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на страницу пользователя</h4>
            </div>
            <div id="one_user_page_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertPublicationComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Отправление жалобы на публикацию</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertPublicationComplaint(this)">
                    <div class="form-group">
                        <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label for="complaint_text">Текст жалобы:</label>
                        <textarea id="complaint_text" class="form-control" name="complaint_text"></textarea>
                        <input class="published_user_id" type="hidden" name="published_user_id">
                        <input class="publication_id" type="hidden" name="publication_id">
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


<?php $this->load->view("footer");?>

<script>

    function insertUserPageEmotion(context) {
        var user_id = context.parentElement.getAttribute('data-user_id');
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        console.log(emotioned_user_id);
        con
//        $.ajax({
//            method: "POST",
//            url: "<?php //echo base_url()?>//publication_emotions/insert_publication_emotion",
//            data: {published_user_id: published_user_id, emotioned_user_id: emotioned_user_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
//            dataType: "JSON"
//        }).done(function (message) {
//            $(".csrf").val(message.csrf_hash);
//            if (message.emotion_num_rows == 0) {
//                $(".emotions_field_" + publication_id).html("<img onclick='deletePublicationEmotion(this)'" +
//                    " src='<?php //echo base_url()?>//uploads/icons/emotioned.png'><span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>" + message.total_emotions + "</span>");
//            } else {
//                alert(message.emotion_error);
//            }
//        })
    }

    function insertGuestMessage(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>guest_messages/insert_guest_message",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#message_text").val('');
            if (message.comment_text != '') {
                $(".messages_by_user").prepend("<div class='one_message_" + message.message_id + "'>" +
                    "<div class='commented_user'>" +
                    "<a href='<?php echo base_url()?>one_user/" + message.user_email + "'>" +
                    "<img src='<?php echo base_url()?>uploads/images/user_images/" + message.user_image + "' class='commented_avatar'>" + message.user_name + " " +
                    "</a>" +
                    "<span class='comment-date-time'>" + message.message_date + " <br>" + message.message_time + "</span>" +
                    "<div onclick='deleteGuestMessage(this)' data-id='" + message.message_id + "' data-guest_id='" + message.guest_id + "' class='right'>X</div>" +
                    "</div>" +
                    "<div class='comment_text'>" + message.comment_text + "</div> " +
                    "</div>");
                $(".no-messages").html('');
            } else {
                alert(message.message_error);
            }
        })
    }

    function deleteGuestMessage(context) {
        var id = context.getAttribute('data-id');
        var guest_id = context.getAttribute('data-guest_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>guest_messages/delete_guest_message",
            data: {id: id, guest_id: guest_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.message_error) {
                alert(message.message_error);
            } else {
                $('.one_message_' + id).fadeOut(500);
            }
        })
    }


    window.onload = function () {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publications/user_publications",
            data: {user_id: <?php echo $user_id?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#publications").html(message.user_publications);
        })
    };

    function insertPublicationComment(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_comments/insert_publication_comment",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#comment_text").val('');
            if (message.comment_text != '') {
                $(".comments_by_publication").prepend("<div class='one_comment_" + message.comment_id + "'>" +
                    "<div class='commented_user'>" +
                    "<a href='<?php echo base_url()?>one_user/" + message.user_email + "'>" +
                    "<img src='<?php echo base_url()?>uploads/images/user_images/" + message.user_image + "' class='commented_avatar'>" + message.user_name + " " +
                    "</a>" +
                    "<span class='comment-date-time'>" + message.comment_date + " <br>" + message.comment_time + "</span>" +
                    "<div onclick='deletePublicationComment(this)' data-publication_comment_id='" + message.comment_id + "' data-publication_id='" + message.publication_id + "' class='right'>X</div>" +
                    "</div>" +
                    "<div class='comment_text'>" + message.comment_text + "</div> " +
                    "</div>");
                $(".comments_field_" + message.publication_id).html("<span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            } else {
                alert(message.comment_error);
            }
        })
    }
    function deletePublicationComment(context) {
        var publication_comment_id = context.getAttribute('data-publication_comment_id');
        var publication_id = context.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_comments/delete_publication_comment",
            data: {id: publication_comment_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + publication_comment_id).fadeOut(500);
                $(".comments_field_" + publication_id).html("<span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            }
        })
    }

    function insertPublicationComplaintPress(context) {
        var published_user_id = context.parentElement.getAttribute('data-published_user_id');
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        var complained_user_id = context.parentElement.getAttribute('data-complained_user_id');
        $('.published_user_id').val(published_user_id);
        $('.publication_id').val(publication_id);
        $('.complained_user_id').val(complained_user_id);
    }

    function insertPublicationComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_complaints/insert_publication_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#complaint_text").val('');
            if (message.complaint_num_rows == 0 && message.complaint_text != '') {
                $("#insertPublicationComplaint").trigger('click');
                alert(message.complaint_success);
                $(".complaints_field_" + message.publication_id).html("");
            } else {
                $("#insertPublicationComplaint").trigger('click');
                alert(message.complaint_error);
            }
        })
    }
    function insertPublicationEmotion(context) {
        var published_user_id = context.parentElement.getAttribute('data-published_user_id');
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_emotions/insert_publication_emotion",
            data: {published_user_id: published_user_id, emotioned_user_id: emotioned_user_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows == 0) {
                $(".emotions_field_" + publication_id).html("<img onclick='deletePublicationEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deletePublicationEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_emotions/delete_publication_emotion",
            data: {emotioned_user_id: emotioned_user_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".emotions_field_" + publication_id).html("<img onclick='insertPublicationEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".emotions_field_" + publication_id).html("<img onclick='insertPublicationEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>" + message.total_emotions + "</span>");
                }
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function insertPublicationImageEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var publication_image_id = context.parentElement.getAttribute('data-publication_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_image_emotions/insert_publication_image_emotion",
            data: {emotioned_user_id: emotioned_user_id, publication_image_id: publication_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.image_emotion_num_rows == 0) {
                $(".image_emotions_field_" + publication_image_id).html("<img class='emotion_image' onclick='deletePublicationImageEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deletePublicationImageEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var publication_image_id = context.parentElement.getAttribute('data-publication_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_image_emotions/delete_publication_image_emotion",
            data: {emotioned_user_id: emotioned_user_id, publication_image_id: publication_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.image_emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".image_emotions_field_" + publication_image_id).html("<img class='emotion_image' onclick='insertPublicationImageEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".image_emotions_field_" + publication_image_id).html("<img class='emotion_image' onclick='insertPublicationImageEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>" + message.total_emotions + "</span>");
                }
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function insertPublicationShare(context) {
        var published_user_id = context.parentElement.getAttribute('data-published_user_id');
        var shared_user_id = context.parentElement.getAttribute('data-shared_user_id');
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_shares/insert_publication_share",
            data: {published_user_id: published_user_id, shared_user_id: shared_user_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.share_num_rows == 0) {
                $(".shares_field_" + publication_id).html("<img onclick='deletePublicationShare(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/shared.png'><span class='badge' onclick='getPublicationShares(this)' data-toggle='modal' data-target='#getPublicationShares'>" + message.total_shares + "</span>");
            } else {
                alert(message.share_error);
            }
        })
    }
    function deletePublicationShare(context) {
        var shared_user_id = context.parentElement.getAttribute('data-shared_user_id');
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_shares/delete_publication_share",
            data: {shared_user_id: shared_user_id, publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.share_num_rows > 0) {
                if (message.total_shares == null) {
                    $(".shares_field_" + publication_id).html("<img onclick='insertPublicationShare(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unshared.png'>");
                } else {
                    $(".shares_field_" + publication_id).html("<img onclick='insertPublicationShare(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unshared.png'><span class='badge' onclick='getPublicationShares(this)' data-toggle='modal' data-target='#getPublicationShares'>" + message.total_shares + "</span>");
                }
            } else {
                alert(message.share_error);
            }
        })
    }


    function getUserPageEmotions(context) {
        var user_id = context.getAttribute('data-user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_page_emotions/index",
            data: {user_id: user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_user_page_emotions").html(message.one_user_page_emotions);
        })
    }

    function loadBlock(context) {
        var id = context.getAttribute('data-id');
        var user_id = context.parentElement.getAttribute('data-user_id');
        var guest_id = "<?php echo $_SESSION['user_id'];?>";
        var link = context.getAttribute('data-link');
        if (link == 'publications/user_publications' || link == 'guest_messages/index' || link == 'publication_shares/user_publication_shares') {
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>" + link,
                data: {user_id: user_id, guest_id: guest_id, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $(".csrf").val(message.csrf_hash);
                if (link == 'publications/user_publications') {
                    $("#publications").html(message.user_publications);
                } else if (link == 'guest_messages/index') {
                    $("#publications").html(message.guest_messages);
                } else if (link == 'publication_shares/user_publication_shares') {
                    $("#publications").html(message.user_publication_shares);
                }
                $('.user-column').removeClass('active-column');
                $('.user-column').eq(id).addClass('active-column');
            })
        } else {
            alert("Не удалось загрузить запрашиваемую страницу!");
        }
    }

    function getPublicationComments(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        var published_user_id = context.parentElement.getAttribute('data-published_user_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_comments/index",
            data: {publication_id: publication_id, published_user_id: published_user_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_comments").html(message.one_publication_comments);
        })
    }
    function getPublicationEmotions(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_emotions/index",
            data: {publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_emotions").html(message.one_publication_emotions);
        })
    }
    function getPublicationImageEmotions(context) {
        var publication_image_id = context.parentElement.getAttribute('data-publication_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_image_emotions/index",
            data: {publication_image_id: publication_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_image_emotions").html(message.one_publication_image_emotions);
        })
    }
    function getPublicationShares(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_shares/index",
            data: {publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_shares").html(message.one_publication_shares);
        })
    }



    $(document).scroll(function(){
        if($(document).scrollTop() > $('header').height ()){
            $('.menu').eq(0).addClass('fixed');
            $('.menu').eq(0).addClass('menu-animated');
            $('#mobileMenu').css('display', 'none');
        }
        else{
            $('.menu').eq(0).removeClass('fixed');
            $('.menu').eq(0).removeClass('menu-animated');
        }

        if($(document).scrollTop() > $('header').height ()){
            $('.phone_logo').eq(0).addClass('fixed');
            $('.phone_logo').eq(0).addClass('menu-animated');
        }
        else{
            $('.phone_logo').eq(0).removeClass('fixed');
            $('.phone_logo').eq(0).removeClass('menu-animated');
        }

        if ($(document).scrollTop() > 100) {
            $('.scrolldown').css('display', 'none');
            $('.scrollup').fadeIn(1000);
        } else {
            $('.scrollup').css('display', 'none');
            $('.scrolldown').fadeIn(1000);
        }
    });

    $('.scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    $('#showMobileInterests').click(function(){
        $('#mobileInterests').slideToggle(500);
    });
    $('#showMobileInfo').click(function(){
        $('#mobileInfo').slideToggle(500);
    });

    $("#getUserBooks").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getUserBooks").modal('hide');
        };
    });
    $("#getUserEvents").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getUserEvents").modal('hide');
        };
    });
    $("#getUserSongs").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getUserSongs").modal('hide');
        };
    });
    $("#getUserGifts").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getUserGifts").modal('hide');
        };
    });
    $("#getUserStakes").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getUserStakes").modal('hide');
        };
    });

    $("#getPublicationComments").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getPublicationComments").modal('hide');
        };
    });
    $("#getPublicationEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getPublicationEmotions").modal('hide');
        };
    });
    $("#getPublicationImageEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getPublicationImageEmotions").modal('hide');
        };
    });
    $("#getPublicationShares").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getPublicationShares").modal('hide');
        };
    });
    $("#insertPublicationComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertPublicationComplaint").modal('hide');
        };
    });

</script>
</body>
</html>