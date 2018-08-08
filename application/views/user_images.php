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

<div class="modal fade" id="getUserImageEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоцию на фотку пользователя</h4>
            </div>
            <div id="one_user_image_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertUserImage" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление новых фотографий в свой альбом</h4>
            </div>
            <div class="modal-body">
                <form onsubmit="insertUserImage(this)" action="javascript:void(0)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input required type="file" class="form-control" multiple name="user_image[]">
                    <input type="hidden" name="album_id" value="<?php echo $current_id?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>">
                    <button class="btn btn-primary center-block" type="submit">Добавить фотографии</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserImage" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление фотографии из своего альбома</h4>
            </div>
            <div class="modal-body">
                <h3>Вы действительно хотите удалить данную фотографию?</h3>
                <form onsubmit="deleteUserImage(this)" action="javascript:void(0)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input type="hidden" class="id" name="id">
                    <input type="hidden" class="album_id" name="album_id">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>">
                    <input type="hidden" class="user_image_file" name="user_image_file">
                    <button class="btn btn-primary center-block" type="submit">Удалить фотографиию</button>
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

    function changeMainImage(context) {
        var album_id = context.getAttribute('data-album_id');
        var user_image_file = context.getAttribute('data-image_file');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_images/change_main_image",
            data: {album_id: album_id, user_image_file: user_image_file, csrf_test_name: $('.csrf').val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.image_error) {
                alert(message.image_error);
            }
            if (message.image_success) {
                alert(message.image_success);
                location.reload(true);
            }
        })
    }

    function deleteUserImagePress(context) {
        var id = context.getAttribute('data-id');
        var album_id = context.getAttribute('data-album_id');
        var user_image_file = context.getAttribute('data-image_file');
        $('.id').val(id);
        $('.album_id').val(album_id);
        $('.user_image_file').val(user_image_file);
    }

    function deleteUserImage(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "user_images/delete_user_image",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteUserImage").trigger('click');
            if (message.album_error) {
                alert(message.album_error);
            }

            if (message.image_success) {
                alert(message.image_success);
                location.reload(true);
            }
        });
    }

    function insertUserImage(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_images/insert_user_image",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.images_error) {
                alert(message.images_error);
            }
            if (message.images_success) {
                alert(message.images_success);
                location.reload(true);
            }
        })
    }

    function getUserImageEmotions(context) {
        var user_image_id = context.parentElement.getAttribute('data-user_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_image_emotions/index",
            data: {user_image_id: user_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_user_image_emotions").html(message.one_user_image_emotions);
        })
    }
    function insertUserImageEmotion(context) {
        var user_id = context.parentElement.getAttribute('data-user_id');
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var user_image_id = context.parentElement.getAttribute('data-user_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_image_emotions/insert_user_image_emotion",
            data: {user_id: user_id, emotioned_user_id: emotioned_user_id, user_image_id: user_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.image_emotion_num_rows == 0) {
                $(".image_emotions_field_" + user_image_id).html("<img class='emotion_image' onclick='deleteUserImageEmotion(this)'" +
                    " src='<?php echo base_url()?>uploads/icons/emotioned.png'><span class='badge' onclick='getUserImageEmotions(this)' data-toggle='modal' data-target='#getUserImageEmotions'>" + message.total_emotions + "</span>");
            } else {
                alert(message.emotion_error);
            }
        })
    }
    function deleteUserImageEmotion(context) {
        var emotioned_user_id = context.parentElement.getAttribute('data-emotioned_user_id');
        var user_image_id = context.parentElement.getAttribute('data-user_image_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>user_image_emotions/delete_user_image_emotion",
            data: {emotioned_user_id: emotioned_user_id, user_image_id: user_image_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.image_emotion_num_rows > 0) {
                if (message.total_emotions == null) {
                    $(".image_emotions_field_" + user_image_id).html("<img class='emotion_image' onclick='insertUserImageEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'>");
                } else {
                    $(".image_emotions_field_" + user_image_id).html("<img class='emotion_image' onclick='insertUserImageEmotion(this)'" +
                        " src='<?php echo base_url()?>uploads/icons/unemotioned.png'><span class='badge' onclick='getUserImageEmotions(this)' data-toggle='modal' data-target='#getUserImageEmotions'>" + message.total_emotions + "</span>");
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