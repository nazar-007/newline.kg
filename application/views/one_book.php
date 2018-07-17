<!DOCTYPE html>
<html>
<head>
    <title>Чтение одной книги</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/books.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_one_book">
            <?php
            if ($book_num_rows != 1) {
                die('Книга удалена или ещё не добавлена!');
            }
            $session_user_id = $_SESSION['user_id'];
            foreach ($one_book as $info_book) {
                $book_id = $info_book->id;
                $book_file = $info_book->book_file;
                echo "<h3 class='centered'>$info_book->book_name</h3>
                    <div class='row'>
                        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                            <div class='book_tr'>
                                <strong class='book_th'>Автор: </strong>
                                <span class='book_td'>$info_book->book_author</span>
                            </div>
                            <div class='book_tr'>
                                <strong class='book_th'>Описание: </strong>
                                <span class='book_td'>$info_book->book_description</span>
                            </div>
                            <div class='book_tr'>
                                <strong class='book_th'>Категория: </strong>
                                <span class='book_td'>$info_book->category_name</span>
                            </div>
                        </div>
                        <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3 small-hidden'>
                        <div class='book_cover'>
                            <img class='book_image absolute_book' src='" . base_url() . "uploads/images/book_images/$info_book->book_image'>             
                        </div>
                        </div>
                    </div>
                        

                    <div class='one_book_actions'>
                        <span class='emotions_field_$book_id' data-emotioned_user_id='$session_user_id' data-book_id='$book_id'>";
                            if ($emotion_num_rows == 0) {
                                echo "<img onclick='insertBookEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>";
                            } else {
                                echo "<img onclick='deleteBookEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>";
                            }
                            echo "<span class='badge' onclick='getBookEmotions(this)' data-toggle='modal' data-target='#getBookEmotions'>$total_emotions</span>
                        </span>
                        <span class='comments_field_$book_id' data-commented_user_id='$session_user_id' data-book_id='$book_id'>
                            <span onclick='getBookComments(this)' data-toggle='modal' data-target='#getBookComments'>
                                <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_comments</span>
                            </span>
                        </span>
                        <span class='fans_field_$book_id' data-fan_user_id='$session_user_id' data-book_id='$book_id'>";
                            if ($fan_num_rows == 0) {
                                echo "<img onclick='insertBookFan(this)' src='" . base_url() . "uploads/icons/not-fan.png'>";
                            } else {
                                echo "<img onclick='deleteBookFan(this)' src='" . base_url(). "uploads/icons/fan.png'>";
                            }
                            echo "<span class='badge' onclick='getBookFans(this)' data-toggle='modal' data-target='#getBookFans'>$total_fans</span>
                            </span>
                            <span>
                                <a href='" . base_url() ."books/download_book?id=$book_id' data-id='$book_id'>
                                    <img src='" . base_url() . "uploads/icons/download.png'>
                                </a>
                            </span>
                            <span class='complaints_field_$book_id' data-complained_user_id='$session_user_id' data-book_id='$book_id'>";
                            if ($complaint_num_rows == 0) {
                                echo "<img onclick='insertBookComplaintPress(this)' data-toggle='modal' data-target='#insertBookComplaint' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
                            }
                            echo "</span>
                            </div>
                            <iframe class='book_file' src='" . base_url() . "uploads/book_files/$book_file'></iframe>";
            }
            ?>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>

<div class="modal fade" id="getBookComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к книге</h4>
            </div>
            <div id="one_book_comments" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getBookEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на книгу</h4>
            </div>
            <div id="one_book_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getBookFans" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, добавившие книгу в любимки</h4>
            </div>
            <div id="one_book_fans" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertBookComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Отправление жалобы на книгу</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertBookComplaint(this)">
                    <div class="form-group">
                        <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label for="complaint_text">Текст жалобы:</label>
                        <input type="text" id="complaint_text" name="complaint_text">
                        <input class="book_id" type="hidden" name="book_id">
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

    function getBookComments(context) {
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_comments/index",
            data: {book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_book_comments").html(message.one_book_comments);
        })
    }
    function getBookEmotions(context) {
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_emotions/index",
            data: {book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_book_emotions").html(message.one_book_emotions);
        })
    }
    function getBookFans(context) {
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_fans/index",
            data: {book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_book_fans").html(message.one_book_fans);
        })
    }

    function insertBookComment(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_comments/insert_book_comment",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#comment_text").val('');
            if (message.comment_text != '') {
                $(".comments_by_book").prepend("<div class='one_comment_" + message.comment_id + "'>" +
                    "<div class='commented_user'>" +
                    "<a href='<?php echo base_url()?>one_user/" + message.user_email + "'>" +
                    "<img src='<?php echo base_url()?>uploads/images/user_images/" + message.user_image + "' class='commented_avatar'>" + message.user_name + " " +
                    "</a>" +
                    "<span class='comment-date-time'>" + message.comment_date + " <br>" + message.comment_time + "</span>" +
                    "<div onclick='deleteBookComment(this)' data-book_comment_id='" + message.comment_id + "' data-book_id='" + message.book_id + "' class='right'>X</div>" +
                    "</div>" +
                    "<div class='comment_text'>" + message.comment_text + "</div> " +
                    "</div>");
                $(".comments_field_" + message.book_id).html("<span onclick='getBookComments(this)' data-toggle='modal' data-target='#getBookComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            } else {
                alert(message.comment_error);
            }
        })
    }

    function deleteBookComment(context) {
        var book_comment_id = context.getAttribute('data-book_comment_id');
        var book_id = context.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_comments/delete_book_comment",
            data: {id: book_comment_id, book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + book_comment_id).remove();
                $(".comments_field_" + book_id).html("<span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>" +
                    "<img src='<?php echo base_url()?>uploads/icons/comment.png'><span class='badge'>" + message.total_comments + "</span></span>");
            }
        })
    }

    function insertBookComplaintPress(context) {
        var book_id = context.parentElement.getAttribute('data-book_id');
        var complained_user_id = context.parentElement.getAttribute('data-complained_user_id');
        $('.book_id').val(book_id);
        $('.complained_user_id').val(complained_user_id);
    }

    function insertBookComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_complaints/insert_book_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#complaint_text").val('');
            if (message.complaint_num_rows == 0) {
                $("#insertBookComplaint").trigger('click');
                alert(message.complaint_success);
                $(".complaints_field_" + message.book_id).html("");
            } else {
                $("#insertBookComplaint").trigger('click');
                alert(message.complaint_error);
                $(".complaints_field_" + message.book_id).html("");
            }
        })
    }

    function insertBookEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_emotions/insert_book_emotion",
            data: {emotioned_user_id: emotioned_user_id, book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows == 0) {
                $(".emotions_field_" + book_id).html("<img onclick='deleteBookEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getBookEmotions(this)' data-toggle='modal' data-target='#getBookEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deleteBookEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_emotions/delete_book_emotion",
            data: {emotioned_user_id: emotioned_user_id, book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".emotions_field_" + book_id).html("<img onclick='insertBookEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".emotions_field_" + book_id).html("<img onclick='insertBookEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getBookEmotions(this)' data-toggle='modal' data-target='#getBookEmotions'>" + message.total_emotions + "</span>");
                }
            } else {
                alert(message.emotion_error);
            }
        })
    }

    function insertBookFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_fans/insert_book_fan",
            data: {fan_user_id: fan_user_id, book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows == 0) {
                $(".fans_field_" + book_id).html("<img onclick='deleteBookFan(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/fan.png'><span class='badge' onclick='getBookFans(this)' data-toggle='modal' data-target='#getBookFans'>" + message.total_fans + "</span>");
            } else {
                alert(message.fan_error);
            }
        })
    }
    function deleteBookFan(context) {
        var fan_user_id = context.parentElement.getAttribute('data-fan_user_id');
        var book_id = context.parentElement.getAttribute('data-book_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_fans/delete_book_fan",
            data: {fan_user_id: fan_user_id, book_id: book_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.fan_num_rows > 0) {
                if (message.total_fans == null) {
                    $(".fans_field_" + book_id).html("<img onclick='insertBookFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'>");
                } else {
                    $(".fans_field_" + book_id).html("<img onclick='insertBookFan(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/not-fan.png'><span class='badge' onclick='getBookFans(this)' data-toggle='modal' data-target='#getBookFans'>" + message.total_fans + "</span>");
                }
            } else {
                alert(message.share_error);
            }
        })
    }

    $("#getBookComments").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getBookComments").modal('hide');
        };
    });
    $("#getBookEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getBookEmotions").modal('hide');
        };
    });
    $("#getPublicationFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getPublicationFans").modal('hide');
        };
    });
    $("#insertBookComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertBookComplaint").modal('hide');
        };
    });
</script>

</body>
</html>
