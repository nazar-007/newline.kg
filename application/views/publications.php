<!DOCTYPE html>
<html>
<head>
    <title>Публикации</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/publications.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3">
            <ul class="catalog">
                <li>
                    <a href="<?php echo base_url()?>my_page">
                        <img src="<?php echo base_url()?>uploads/icons/internet.png">
                        Моя страница
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>friends">
                        <img src="<?php echo base_url()?>uploads/icons/notebook.png">
                        Друзья
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>guests">
                        <img src="<?php echo base_url()?>uploads/icons/notebook.png">
                        Гости
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>books">
                        <img src="<?php echo base_url()?>uploads/icons/books.png">
                        Книги
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>events">
                        <img src="<?php echo base_url()?>uploads/icons/calendar.png">
                        События
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>songs">
                        <img src="<?php echo base_url()?>uploads/icons/quaver.png">
                        Песни
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>albums">
                        <img src="<?php echo base_url()?>uploads/icons/pictures.png">
                        Альбомы
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>folders">
                        <img src="<?php echo base_url()?>uploads/icons/folder.png">
                        Папки
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>gifts">
                        <img src="<?php echo base_url()?>uploads/icons/rewards.png">
                        Подарки
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>stakes">
                        <img src="<?php echo base_url()?>uploads/icons/medal.png">
                        Награды
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>users/logout">
                        Выйти
                    </a>
                </li>
                <li>
                    <img src="/uploads/icons/up-arrow.png" class="up-arrow scrollup">
                </li>
            </ul>
        </div>
        <div class="pos_publications col-xs-6 col-sm-9">
            <div id="all_publications">
            <?php
                echo $publications;
            ?>
            </div>
        </div>
        <div class="pos_recommendations small-hidden middle-hidden col-xs-3">
            <div class="rec_post">
                <div class="one_rec_post">
                    <a class="bout_post">
                        Smc,l ld,lc;osmdic mmmskd kmsdmksdmckmdciv...
                        <img src="<?php echo base_url()?>uploads/icons/davcode.jpg">
                    </a>
                    <hr>
                </div>
                <a class="bout_post2">
                    Smc,l ld,lc;osmdic mmmskd kmsdmksdmckmdciv...
                    <img src="<?php echo base_url()?>uploads/icons/davcode.jpg">
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewOnePublication" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр публикации</h4>
            </div>
            <div id="one_publication" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewPublicationEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции</h4>
            </div>
            <div id="one_publication_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewPublicationShares" role="dialog">
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
                        <input type="text" id="complaint_text" name="complaint_text">
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

<?php $this->load->view('footer');?>

<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>
    var offset = 0;
    $(document).scroll(function(){
        if($(document).scrollTop() >= $(document).height() - $(window).height()) {
            offset = offset + 2;
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>publications/loadmore_publications",
                data: {offset: offset, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $('.csrf').val(message.csrf_hash);
                $("#all_publications").append(message.loadmore);
            })
        }
    });

    function getOnePublication(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publications/get_one_publication",
            data: {id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication").html(message.one_publication);
        })
    }

    function getPublicationEmotions(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_emotions/get_publication_emotions",
            data: {publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_emotions").html(message.publication_emotions);
        })
    }

    function getPublicationShares(context) {
        var publication_id = context.parentElement.getAttribute('data-publication_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publication_shares/get_publication_shares",
            data: {publication_id: publication_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_publication_shares").html(message.publication_shares);
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
                $(".comments_field_" + message.publication_id).html("<img src='<?php echo base_url()?>uploads/icons/comment.png'>" + message.total_comments);
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
            $('.one_comment_' + publication_comment_id).remove();
            $(".comments_field_" + publication_id).html("<img src='<?php echo base_url()?>uploads/icons/comment.png'>" + message.total_comments);
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
            if (message.complaint_num_rows == 0) {
                $("#insertPublicationComplaint").trigger('click');
                alert(message.complaint_success);
                $(".complaints_field_" + message.publication_id).html("");
            } else {
                $("#insertPublicationComplaint").trigger('click');
                alert(message.complaint_error);
                $(".complaints_field_" + message.publication_id).html("");
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
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#viewPublicationEmotions'>" + message.total_emotions + "</span>");
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
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#viewPublicationEmotions'>" + message.total_emotions + "</span>");
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
                    " src='<?php echo base_url()?>uploads/icons/shared.png'><span onclick='getPublicationShares(this)' data-toggle='modal' data-target='#viewPublicationShares'>" + message.total_shares + "</span>");
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
                        " src='<?php echo base_url()?>uploads/icons/unshared.png'><span onclick='getPublicationShares(this)' data-toggle='modal' data-target='#viewPublicationShares'>" + message.total_shares + "</span>");
                }
            } else {
                alert(message.share_error);
            }
        })
    }

</script>
</body>
</html>