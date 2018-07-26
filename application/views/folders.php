<!DOCTYPE html>
<html>
<head>
    <title>Мои папки</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/folders_documents.css">
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
        <div class="pos_folders col-xs-6 col-sm-9">
            <h1 class="centered">Мои папки</h1>
            <button class="btn btn-primary center-block" data-toggle="modal" data-target="#insertFolder">Добавить папку</button>
            <br>
            <div class="folders">
                <?php
                foreach ($folders as $folder) {
                    $session_user_id = $_SESSION['user_id'];
                    echo "<div class='one_folder_$folder->id folder row'>
                            <div class='col-xs-8'>
                                <a href='" . base_url() . "documents/$folder->id'>
                                    <img src='" . base_url() . "uploads/icons/folder.png'>
                                    <span class='one_folder_name_$folder->id folder_name'>
                                        $folder->folder_name    
                                    </span>
                                </a>
                            </div>";
                            if ($folder->folder_name != "My Folder") {
                                echo "<div class='col-xs-4'>
                                    <span class='right'>
                                        <button onclick='deleteFolderPress(this)' data-toggle='modal' data-target='#deleteFolder' data-id='$folder->id' data-folder_name='$folder->folder_name' class='btn btn-danger'>
                                            <span class='glyphicon glyphicon-trash'></span>
                                        </button>
                                        <button onclick='updateFolderPress(this)' data-toggle='modal' data-target='#updateFolder' data-id='$folder->id' data-folder_name='$folder->folder_name' class='btn btn-warning'>
                                            <span class='glyphicon glyphicon-edit'></span>
                                        </button>
                                    </span>
                                </div>";
                            }
                    echo "</div>";
                }
            ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertFolder" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление новой папки</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertFolder(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label>Название новой папки</label>
                        <input type="text" class="form-control folder_name" name="folder_name">
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

<div class="modal fade" id="deleteFolder" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление моей папки</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteFolder(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="folder_name" type="hidden">
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

<div class="modal fade" id="updateFolder" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Редактирование папки</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="updateFolder(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="update_id" name="id" type="hidden">
                        <label>Название папки</label>
                        <input class="update_name form-control" name="folder_name" type="text">
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

    function insertFolder(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "folders/insert_folder",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $(".folder_name").val('');
            if (message.folder_name == "My Folder") {
                alert(message.folder_error);
            } else {
                $("#insertFolder").trigger('click');
                $(".folders").append("<div class='one_folder_" + message.id + " folder row'>" +
                    "<div class='col-xs-8'> " +
                        "<a href='<?php echo base_url()?>documents/" + message.id + "'>" +
                        "<img src='<?php echo base_url()?>uploads/icons/folder.png'>" +
                            "<span class='one_folder_name_" + message.id + " folder_name'>" + message.folder_name + "</span> " +
                        "</a>" +
                    "</div>" +
                    "<div class='col-xs-4'>" +
                        "<span class='right'> " +
                            "<button onclick='deleteFolderPress(this)' data-toggle='modal' data-target='#deleteFolder' data-id='" + message.id + "' data-folder_name='" + message.folder_name + "' class='btn btn-danger'>" +
                                "<span class='glyphicon glyphicon-trash'></span>" +
                            "</button>" +
                            "<button onclick='updateFolderPress(this)' data-toggle='modal' data-target='#updateFolder' data-id='" + message.id + "' data-folder_name='" + message.folder_name + "' class='btn btn-warning'>" +
                                "<span class='glyphicon glyphicon-edit'></span>" +
                            "</button>" +
                        "</span>" +
                    "</div>");
            }
        })
    }

    function deleteFolderPress(context) {
        var id = context.getAttribute('data-id');
        var folder_name = context.getAttribute('data-folder_name');
        $(".delete_id").val(id);
        $(".delete_name").val(folder_name);
        $(".delete_question").html("Вы действительно хотите удалить Вашу папку " + folder_name + ' со всем её содержимым?');
    }

    function deleteFolder(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "folders/delete_folder",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteFolder").trigger('click');
            if (message.folder_error) {
                alert(message.folder_error);
            } else {
                $(".one_folder_" + message.id).remove();
            }
        });
    }
    function updateFolderPress(context) {
        var id = context.getAttribute('data-id');
        var folder_name = context.getAttribute('data-folder_name');
        $(".update_id").val(id);
        $(".update_name").val(folder_name);
    }

    function updateFolder(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "folders/update_folder",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $(".folder_name").val('');
            if (message.folder_name == 'My Folder') {
                alert(message.folder_error);
            } else {
                $("#updateFolder").trigger('click');
                $(".one_folder_name_" + message.id).html(message.folder_name);
            }

        })
    }

    $("#insertFolder").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertFolder").modal('hide');
        };
    });
    $("#deleteFolder").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteFolder").modal('hide');
        };
    });
    $("#updateFolder").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#updateFolder").modal('hide');
        };
    });
</script>
</body>
</html>