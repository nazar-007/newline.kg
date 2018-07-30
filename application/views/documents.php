<!DOCTYPE html>
<html>
<head>
    <title>Мои документы</title>
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
        <div class="pos_documents col-xs-6 col-sm-9">
            <?php
            if ($documents_error != '') {
                echo "<h1 class='red centered'>" . $documents_error . "
                <br><a href='" . base_url() . "folders'>Назад</a></h1>";
                die();
            }
            ?>
            <h1 class="centered">Мои документы</h1>
            <button class="btn btn-primary center-block" data-toggle="modal" data-target="#insertDocument">Добавить документ</button>
            <br>
            <div class="documents">
                <div class='document row'>
                    <div class='col-xs-12'>
                        <a href='<?php echo base_url()?>folders'>
                            <img src='<?php echo base_url()?>uploads/icons/folder.png'>
                            <span class='document_name'>
                                Все мои папки
                            </span>
                        </a>
                    </div>
                </div>
                <?php
                    foreach ($documents as $document) {
                        $session_user_id = $_SESSION['user_id'];
                        echo "<div class='one_document_$document->id document row'>
                                <div class='col-xs-6'>
                                    <a href='" . base_url() . "one_document/$document->folder_id/$document->id/'>
                                        <img src='" . base_url() . "uploads/icons/document.png'>
                                        <span class='document_name'>
                                            $document->document_name
                                        </span>
                                    </a>
                                </div>
                                <div class='col-xs-2 document_date'>
                                    $document->document_date <br> $document->document_time
                                </div>
                                <div class='col-xs-4'>
                                    <span class='right'>
                                        <button onclick='deleteDocumentPress(this)' data-toggle='modal' data-target='#deleteDocument' class='btn btn-danger' data-id='$document->id' data-document_name='$document->document_name'>
                                            <span class='glyphicon glyphicon-trash'></span>
                                        </button>
                                    </span>
                                </div>
                            </div>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertDocument" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление нового документа</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertDocument(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <label>Название нового документа</label>
                        <input type="text" class="form-control document_name" name="document_name">
                        <input type="hidden" name="folder_id" value="<?php echo $folder_id?>">
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

<div class="modal fade" id="deleteDocument" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление моего документа</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteDocument(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="document_name" type="hidden">
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

<?php $this->load->view("footer");?>

<script>
    function insertDocument(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "documents/insert_document",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $(".document_name").val('');
            if (message.document_error) {
                alert(message.document_error);
            } else {
                $("#insertDocument").trigger('click');
                $(".documents").append("<div class='one_document_" + message.id + " document row'>" +
                    "<div class='col-xs-6'>" +
                        "<a href='<?php echo base_url()?>one_document/" + message.folder_id + "/" + message.id + "'>" +
                        "<img src='<?php echo base_url()?>uploads/icons/document.png'>" +
                       "<span class='document_name'>" + message.document_name + "</span>" +
                        "</a>" +
                    "</div>" +
                    "<div class='col-xs-2 document_date'>" + message.document_date + "<br>" + message.document_time + "</div>" +
                    "<div class='col-xs-4'>" +
                        "<span class='right'>" +
                            "<button onclick='deleteDocumentPress(this)' data-toggle='modal' data-target='#deleteDocument' class='btn btn-danger' data-id='" + message.id + "' data-document_name='" + message.document_name + "'>" +
                                "<span class='glyphicon glyphicon-trash'></span>" +
                            "</button>" +
                        "</span>" +
                    "</div>" +
                    "</div>");
            }
        })
    }

    function deleteDocumentPress(context) {
        var id = context.getAttribute('data-id');
        var document_name = context.getAttribute('data-document_name');
        $(".delete_id").val(id);
        $(".delete_name").val(document_name);
        $(".delete_question").html("Вы действительно хотите удалить Ваш документ " + document_name + '?');
    }

    function deleteDocument(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "documents/delete_document",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteDocument").trigger('click');
            if (message.document_error) {
                alert(message.document_error);
            } else {
                $(".one_document_" + message.id).remove();
            }
        });
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


    $("#insertDocument").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertDocument").modal('hide');
        };
    });
    $("#deleteDocument").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteDocument").modal('hide');
        };
    });
</script>
</body>
</html>