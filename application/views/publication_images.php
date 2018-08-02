<!DOCTYPE html>
<html>
<head>
    <title>Фотографии</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/images.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/publications.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-sm-3 col-md-9 col-lg-9">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_images col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row" id="all_images">
                    <?php if ($album_num_rows != 1) {
                        die("<h3 class='centered'>Альбом удалён или ещё не создан");
                    }

                    echo $images;
                    ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getPublicationImageEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоцию на фотку публикации</h4>
            </div>
            <div id="one_publication_image_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>


<script>

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

</script>

</body>
</html>