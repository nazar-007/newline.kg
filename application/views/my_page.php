<!DOCTYPE html>
<html>
<head>
    <title>Моя страница</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/publications.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/users.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_my_page">
            <div class="row" id="my_page">
                <?php foreach ($users as $user):?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 about">
                    <a href="<?php echo base_url()?>albums">
                        <img class="img-thumbnail" src="<?php echo base_url()?>uploads/images/user_images/<?php echo $user->main_image?>">
                    </a>
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
                    <img onclick="insertUserPageEmotion(this)" src="<?php echo base_url()?>uploads/icons/unemotioned.png">
                    <span data-toggle="modal" data-target="#getUserPageEmotions" onclick="getUserPageEmotions(this)" data-user_id="<?php echo $_SESSION['user_id']?>" class="badge"><?php echo $total_user_page_emotions?></span>
                    <button class="btn btn-default">
                        <a href='<?php echo base_url()?>update'>
                            <img src="<?php echo base_url()?>uploads/icons/update.png">
                        </a>
                    </button>
                    <button class="btn btn-danger btn-delete" data-toggle="modal" data-target="#deleteUser">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                    <img id="showMobileInterests" class="middle-hidden big-hidden huge-hidden" src="/uploads/icons/world.png">
                    <div id="mobileInterests" class="small-hidden interests">
                        <div class="one-interest">
                            <button data-toggle="modal" data-target="#insertPublication" class="btn btn-primary btn-interests">
                                Добавить публикацию
                            </button>
                        </div>
                        <div class="one-interest">
                            <button class="btn btn-primary btn-interests">
                                <a class="white" href="<?php echo base_url()?>albums">Загрузить фотографии</a>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <div class="row" data-user_id="<?php echo $_SESSION['user_id']?>">
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


<div class="modal fade" id="getPublicationComments" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Комментарии к Вашей публикации</h4>
            </div>
            <div id="one_publication_comments" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление страницы</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url()?>users/delete_user">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']?>">
                    <h3>Вы действительно хотите удалить свою страницу со всеми данными?</h3>
                    <span class="action_buttons">
                        <button class="btn btn-primary" type="submit">ОК, удалить</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    </span>
                </form>
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
                <h4 class="modal-title">Люди, поставившие эмоции на Вашу публикацию</h4>
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
                <h4 class="modal-title">Люди, поставившие эмоцию на фотку Вашей публикации</h4>
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
                <h4 class="modal-title">Люди, поделившиеся Вашей публикацией</h4>
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
                <h4 class="modal-title">Люди, поставившие эмоции на Вашу страницу</h4>
            </div>
            <div id="one_user_page_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertPublication" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление моей публикации</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertPublication(this)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <label>Заголовок публикации</label>
                    <textarea required class="form-control" rows="3" name="publication_name"></textarea>
                    <label>Описание публикации</label>
                    <textarea required class="form-control" rows="15" name="publication_description"></textarea>
                    <label>Фотки к публикации</label>
                    <input type="file" multiple class="form-control" name="publication_image[]">
                    <input type="hidden" name="published_user_id" value="<?php echo $_SESSION['user_id']?>">
                    <span class="action_buttons">
                        <button class="btn btn-primary" type="submit">ОК, добавить</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    </span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePublication" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление моей публикации</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deletePublication(this)">
                    <h3>Вы действительно хотите удалить свою публикацию со всеми лайками, репостами, комментами?</h3>
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input type="hidden" class="publication_id" name="id">
                    <input type="hidden" class="published_user_id" name="published_user_id">
                    <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
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
        alert("Вы не можете поставить эмоцию на свою страницу");
    }

    function deletePressPublication(context) {
        var id = context.getAttribute('data-id');
        var published_user_id = context.getAttribute('data-published_user_id');
        $(".publication_id").val(id);
        $(".published_user_id").val(published_user_id);
    }

    function insertPublication(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publications/insert_publication",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.publication_error) {
                alert(message.publication_error);
            }
            if (message.publication_success) {
                alert(message.publication_success);
                location.reload(true);
            }
        })
    }

    function deletePublication(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publications/delete_publication_by_published_user_id",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.publication_error) {
                alert(message.publication_error);
            } else {
                $("#deletePublication").trigger('click');
                $(".one_pub_" + message.id).fadeOut(500);
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
            data: {user_id: <?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#publications").html(message.user_publications);
        });
    };

    function insertPublicationEmotion(context) {
        alert("Вы не можете поставить эмоцию на свою публикацию");
    }

    function insertPublicationImageEmotion(context) {
        alert("Вы не можете поставить эмоцию на фотку своей публикации");
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
            url: "<?php echo base_url()?>publication_comments/delete_publication_comment_by_published_user_id",
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

    function insertPublicationShare(context) {
        alert("Вы не можете поделиться своей публикацией");
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
    $("#insertPublication").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertPublication").modal('hide');
        };
    });

</script>
</body>
</html>