<!DOCTYPE html>
<html>
<head>
    <title>Просмотр одной публикации</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/publications.css">
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
        <div class="pos_one_publication">
            <div class="navigation">
                <?php
                $prev = $current_id - 1;
                $next = $current_id + 1;

                if ($current_id > 1) {
                    echo "<a href='" . base_url() . "one_publication/$prev'>Предыдущая публикация</a>";
                }
                   echo "<a class='right' href='" . base_url() . "one_publication/$next'>Следующая публикация</a>";

                ?>
            </div>
            <?php
            if ($publication_num_rows != 1) {
                die("<h3 class='centered'>Публикация удалена или ещё не добавлена!</h3>");
            }
            $session_user_id = $_SESSION['user_id'];
            foreach ($one_publication as $publication) {
                $published_user_id = $publication->published_user_id;
                $publication_id = $publication->id;
                echo "<div class='one_publication'>
                        <div class='publication'>
                            <div class='bout_user'>
                                <a class='user_name' href='" . base_url() . "one_user/$publication->email'>" . $publication->nickname . " " . $publication->surname . "
                                    <img src='" . base_url() . "uploads/images/user_images/" . $publication->main_image . "' class='user_avatar'>
                                </a>
                                <span class='publication-date-time'>$publication->publication_date<br>$publication->publication_time</span>
                            </div>
                            <div class='user_publication'>
                                <h4 class='publication_name'>$publication->publication_name</h4>
                                <div class='publication_description'>" .
                    $publication->publication_description . "
                                </div>
                            </div>";
                if (count($publication_images) > 0) {
                    echo "<div id='carousel_$publication_id' class='carousel slide' data-interval='false' data-ride='carousel'>
                            <div class='carousel-inner'>";
                    foreach ($publication_images as $key => $publication_image) {
                        $publication_image_id = $publication_image->id;
                        if ($key == 0) {
                            echo "<div class='item active'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='initial; margin: 0 auto;'>
                                        </div>";
                        } else {
                            echo "<div class='item'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='initial; margin: 0 auto'>
                                        </div>";
                        }
                    }
                    if (count($publication_images) > 1) {
                        echo "<a class='left carousel-control' href='#carousel_$publication_id' data-slide='prev'>
                              <span class='glyphicon glyphicon-chevron-left'></span>
                              <span class='sr-only'>Previous</span>
                            </a>
                            <a class='right carousel-control' href='#carousel_$publication_id' data-slide='next'>
                              <span class='glyphicon glyphicon-chevron-right'></span>
                              <span class='sr-only'>Next</span>
                            </a>";
                    }
                    echo "</div>
                    </div>";
                }
                echo "</div></div>
                    <div class='actions'>
                        <span class='emotions_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($emotion_num_rows == 0) {
                    echo "<img onclick='insertPublicationEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                } else {
                    echo "<img onclick='deletePublicationEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                }
                echo "<span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>$total_emotions</span>
                        </span>
                        <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-commented_user_id='$session_user_id' data-publication_id='$publication_id'>
                            <span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>
                                <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_comments</span>
                            </span>
                        </span>
                        <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($share_num_rows == 0) {
                    echo "<img onclick='insertPublicationShare(this)' src='" . base_url() . "uploads/icons/unshared.png'>";
                } else {
                    echo "<img onclick='deletePublicationShare(this)' src='" . base_url() . "uploads/icons/shared.png'>";
                }
                echo "<span class='badge' onclick='getPublicationShares(this)' data-toggle='modal' data-target='#getPublicationShares'>$total_shares</span>
                        </span>
                        <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($complaint_num_rows == 0) {
                    echo "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' src='" . base_url() . "uploads/icons/complaint.png' class='right'>";
                }
                echo "</span></div></div>";
            }
            ?>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>

<div class="modal fade" id="getPublicationComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к публикации</h4>
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
                <h4 class="modal-title">Люди, поставившие эмоции на публикацию</h4>
            </div>
            <div id="one_publication_emotions" class="modal-body">

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
                <h4 class="modal-title">Люди, поделившиеся публикацией</h4>
            </div>
            <div id="one_publication_shares" class="modal-body">

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


<script>

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
    window.onblur = function () {console.log('неактивен')};
    window.onfocus = function () {console.log('снова активен')};


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
