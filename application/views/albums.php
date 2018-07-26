<!DOCTYPE html>
<html>
<head>
    <title>Мои альбомы</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/albums_images.css">
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
        <div class="pos_albums col-xs-6 col-sm-9">
            <h1 class="centered">Мои альбомы</h1>
            <button class="btn btn-primary center-block" data-toggle="modal" data-target="#insertAlbum">Добавить альбом</button>
            <div class="albums row">
                <?php
                foreach ($albums as $album) {
                    $session_user_id = $_SESSION['user_id'];
                    echo "<div class='col-xs-6 col-sm-6 col-md-3 col-lg-3 one_album_$album->id'>
                                <a href='" . base_url() . "images/$album->id'>
                                    <img src='" . base_url() . "uploads/icons/my_album.png'>
                                    <div class='one_album_name_$album->id album_name'>
                                        $album->album_name    
                                    </div>
                                </a>";
                    if ($album->album_name != "My Album" && $album->album_name != "Publication Album") {
                        echo "<span>
                                        <button onclick='deleteAlbumPress(this)' data-toggle='modal' data-target='#deleteAlbum' data-id='$album->id' data-album_name='$album->album_name' class='btn btn-danger'>
                                            <span class='glyphicon glyphicon-trash'></span>
                                        </button>
                                        <button onclick='updateAlbumPress(this)' data-toggle='modal' data-target='#updateAlbum' data-id='$album->id' data-album_name='$album->album_name' class='btn btn-warning'>
                                            <span class='glyphicon glyphicon-edit'></span>
                                        </button>
                                </span>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertAlbum" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление нового альбома</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertAlbum(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label>Название нового альбома</label>
                        <input type="text" class="form-control album_name" name="album_name">
                        <br>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, добавить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAlbum" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление моего альбома</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteAlbum(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="album_name" name="album_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateAlbum" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Редактирование альбома</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="updateAlbum(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="update_id" name="id" type="hidden">
                        <label>Название альбома</label>
                        <input class="update_name form-control" name="album_name" type="text">
                        <br>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, редактировакть</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
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


<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>
    function insertAlbum(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "albums/insert_album",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $(".album_name").val('');
            if (message.album_error) {
                alert(message.album_error);
            } else {
                $("#insertAlbum").trigger('click');
                $(".albums").append("<div class='col-xs-6 col-sm-6 col-md-3 col-lg-3 one_album_" + message.id + "'>" +
                    "<a href='<?php echo base_url()?>images/" + message.id + "'>" +
                        "<img src='<?php echo base_url()?>uploads/icons/my_album.png'>" +
                        "<div class='one_album_name_" + message.id + " album_name'>" + message.album_name + "</div>" +
                    "</a>" +
                "<span> " +
                    "<button onclick='deleteAlbumPress(this)' data-toggle='modal' data-target='#deleteAlbum' data-id='" + message.id + "' data-album_name='" + message.album_name + "' class='btn btn-danger'> " +
                        "<span class='glyphicon glyphicon-trash'></span> " +
                    "</button> " +
                    "<button onclick='updateAlbumPress(this)' data-toggle='modal' data-target='#updateAlbum' data-id='" + message.id + "' data-album_name='" + message.album_name + "' class='btn btn-warning'> " +
                        "<span class='glyphicon glyphicon-edit'></span> " +
                    "</button> " +
                    "</span>" +
                "</div>");
            }
        })
    }

    function deleteAlbumPress(context) {
        var id = context.getAttribute('data-id');
        var album_name = context.getAttribute('data-album_name');
        $(".delete_id").val(id);
        $(".delete_name").val(album_name);
        $(".delete_question").html("Вы действительно хотите удалить Ваш альбом " + album_name + ' со всем её содержимым?');
    }

    function deleteAlbum(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "albums/delete_albim",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteAlbum").trigger('click');
            if (message.album_error) {
                alert(message.album_error);
            } else {
                $(".one_album_" + message.id).remove();
            }
        });
    }
    function updateAlbumPress(context) {
        var id = context.getAttribute('data-id');
        var album_name = context.getAttribute('data-album_name');
        $(".update_id").val(id);
        $(".update_name").val(album_name);
    }

    function updateAlbum(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "albums/update_album",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $(".album_name").val('');
            if (message.album_name == 'My Folder') {
                alert(message.album_error);
            } else {
                $("#updateAlbum").trigger('click');
                $(".one_album_name_" + message.id).html(message.album_name);
            }

        })
    }

    $("#insertAlbum").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertAlbum").modal('hide');
        };
    });
    $("#deleteAlbum").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteAlbum").modal('hide');
        };
    });
    $("#updateAlbum").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#updateAlbum").modal('hide');
        };
    });
</script>
</body>
</html>