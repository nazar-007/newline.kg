<!DOCTYPE html>
<html>
<head>
    <title>Просмотр одного события</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/events.css">
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
        <div class="pos_one_event">
            <div class="navigation">
                <?php
                $prev = $current_id - 1;
                $next = $current_id + 1;

                if ($current_id > 1) {
                    echo "<a href='" . base_url() . "one_event/$prev'>Предыдущее событие</a>";
                }
                echo "<a class='right' href='" . base_url() . "one_event/$next'>Следующее событие</a>";

                ?>
            </div>
            <?php
            if ($event_num_rows != 1) {
                die("<h3 class='centered'>Событие уже удалено или ещё не создано!</h3>");
            }
            $session_user_id = $_SESSION['user_id'];
            foreach ($one_event as $info_event) {
                $event_id = $info_event->id;
                echo "<h3 class='centered'>$info_event->event_name</h3>
                        <div>
                            <div>
                                <strong class='event_th'>Описание: </strong>
                                <span class='event_td'>$info_event->event_description</span>
                            </div>
                            <div>
                                <strong class='event_th'>Место события: </strong>
                                <span class='event_td'>$info_event->event_address</span>
                            </div>
                            <div>
                                <strong class='event_th'>Дата: </strong>
                                <span class='event_td'>$info_event->event_start_date</span>
                            </div>
                            <div>
                                <strong class='event_th'>Время: </strong>
                                <span class='event_td'>$info_event->event_start_time</span>
                            </div>
                            <div>
                                <strong class='event_th'>Категория: </strong>
                                <span class='event_td'>$info_event->category_name</span>
                            </div>
                        </div>
                                                

                    <div class='one_event_actions'>
                        <span class='emotions_field_$event_id' data-emotioned_user_id='$session_user_id' data-event_id='$event_id'>";
                if ($emotion_num_rows == 0) {
                    echo "<img onclick='insertEventEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>";
                } else {
                    echo "<img onclick='deleteEventEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>";
                }
                echo "<span class='badge' onclick='getEventEmotions(this)' data-toggle='modal' data-target='#getEventEmotions'>$total_emotions</span>
                        </span>
                        <span class='comments_field_$event_id' data-commented_user_id='$session_user_id' data-event_id='$event_id'>
                            <span onclick='getEventComments(this)' data-toggle='modal' data-target='#getEventComments'>
                                <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_comments</span>
                            </span>
                        </span>
                        <span class='fans_field_$event_id' data-fan_user_id='$session_user_id' data-event_id='$event_id'>";
                if ($fan_num_rows == 0) {
                    echo "<img onclick='insertEventFan(this)' src='" . base_url() . "uploads/icons/not-fan.png'>";
                } else {
                    echo "<img onclick='deleteEventFan(this)' src='" . base_url(). "uploads/icons/fan.png'>";
                }
                echo "<span class='badge' onclick='getEventFans(this)' data-toggle='modal' data-target='#getEventFans'>$total_fans</span>
                            </span>
                            <span class='complaints_field_$event_id' data-complained_user_id='$session_user_id' data-event_id='$event_id'>";
                if ($complaint_num_rows == 0) {
                    echo "<img onclick='insertEventComplaintPress(this)' data-toggle='modal' data-target='#insertEventComplaint' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
                }
                echo "</span>
                            </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>

<div class="modal fade" id="getEventComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к событию</h4>
            </div>
            <div id="one_event_comments" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getEventEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на событие</h4>
            </div>
            <div id="one_event_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getEventFans" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, планирующие пойти на событие</h4>
            </div>
            <div id="one_event_fans" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertEventComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Отправление жалобы на событие</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertEventComplaint(this)">
                    <div class="form-group">
                        <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label for="complaint_text">Текст жалобы:</label>
                        <textarea id="complaint_text" class="form-control" name="complaint_text"></textarea>
                        <input class="event_id" type="hidden" name="event_id">
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

    function getEventComments(context) {
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_comments/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_comments").html(message.one_event_comments);
        })
    }
    function getEventEmotions(context) {
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_emotions/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_emotions").html(message.one_event_emotions);
        })
    }
    function getEventFans(context) {
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_fans").html(message.one_event_fans);
        })
    }

    function insertEventComment(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_comments/insert_event_comment",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#comment_text").val('');
            if (message.comment_text != '') {
                $(".comments_by_event").prepend("<div class='one_comment_" + message.comment_id + "'>" +
                    "<div class='commented_user'>" +
                    "<a href='<?php echo base_url()?>one_user/" + message.user_email + "'>" +
                    "<img src='<?php echo base_url()?>uploads/images/user_images/" + message.user_image + "' class='commented_avatar'>" + message.user_name + " " +
                    "</a>" +
                    "<span class='comment-date-time'>" + message.comment_date + " <br>" + message.comment_time + "</span>" +
                    "<div onclick='deleteEventComment(this)' data-event_comment_id='" + message.comment_id + "' data-event_id='" + message.event_id + "' class='right'>X</div>" +
                    "</div>" +
                    "<div class='comment_text'>" + message.comment_text + "</div> " +
                    "</div>");
                $(".comments_field_" + message.event_id).html("<span onclick='getEventComments(this)' data-toggle='modal' data-target='#getEventComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            } else {
                alert(message.comment_error);
            }
        })
    }

    function deleteEventComment(context) {
        var event_comment_id = context.getAttribute('data-event_comment_id');
        var event_id = context.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_comments/delete_event_comment",
            data: {id: event_comment_id, event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + event_comment_id).fadeOut(500);
                $(".comments_field_" + event_id).html("<span onclick='getEventComments(this)' data-toggle='modal' data-target='#getEventComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            }
        })
    }

    function insertEventComplaintPress(context) {
        var event_id = context.parentElement.getAttribute('data-event_id');
        var complained_user_id = context.parentElement.getAttribute('data-complained_user_id');
        $('.event_id').val(event_id);
        $('.complained_user_id').val(complained_user_id);
    }

    function insertEventComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_complaints/insert_event_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#complaint_text").val('');
            if (message.complaint_num_rows == 0 && message.complaint_text != '') {
                $("#insertEventComplaint").trigger('click');
                alert(message.complaint_success);
                $(".complaints_field_" + message.event_id).html("");
            } else {
                $("#insertEventComplaint").trigger('click');
                alert(message.complaint_error);
            }
        })
    }

    function insertEventEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_emotions/insert_event_emotion",
            data: {emotioned_user_id: emotioned_user_id, event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows == 0) {
                $(".emotions_field_" + event_id).html("<img onclick='deleteEventEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getEventEmotions(this)' data-toggle='modal' data-target='#getEventEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deleteEventEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_emotions/delete_event_emotion",
            data: {emotioned_user_id: emotioned_user_id, event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".emotions_field_" + event_id).html("<img onclick='insertEventEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".emotions_field_" + event_id).html("<img onclick='insertEventEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getEventEmotions(this)' data-toggle='modal' data-target='#getEventEmotions'>" + message.total_emotions + "</span>");
                }
            } else {
                alert(message.emotion_error);
            }
        })
    }

    function insertEventFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/insert_event_fan",
            data: {fan_user_id: fan_user_id, event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows == 0) {
                $(".fans_field_" + event_id).html("<img onclick='deleteEventFan(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/fan.png'><span class='badge' onclick='getEventFans(this)' data-toggle='modal' data-target='#getEventFans'>" + message.total_fans + "</span>");
            } else {
                alert(message.fan_error);
            }
        })
    }
    function deleteEventFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var event_id = context.parentElement.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/delete_event_fan",
            data: {fan_user_id: fan_user_id, event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows > 0) {
                if (message.total_fans == null) {
                    $(".fans_field_" + event_id).html("<img onclick='insertEventFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'>");
                } else {
                    $(".fans_field_" + event_id).html("<img onclick='insertEventFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'><span class='badge' onclick='getBookFans(this)' data-toggle='modal' data-target='#getBookFans'>" + message.total_fans + "</span>");
                }
            } else {
                alert(message.share_error);
            }
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


    $('#showMobileCategories').click(function(){
        $('#mobileCategories').slideToggle(500);
    });


    $("#getEventComments").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getEventComments").modal('hide');
        };
    });
    $("#getEventEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getEventEmotions").modal('hide');
        };
    });
    $("#getEventFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getEventFans").modal('hide');
        };
    });
    $("#insertEventComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertEventComplaint").modal('hide');
        };
    });
</script>

</body>
</html>