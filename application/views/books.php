<!DOCTYPE html>
<html>
<head>
    <title>Книги</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <div class="pos_catalog small-hidden col-sm-3 col-md-3 col-lg-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_books col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="row">
                <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
                    <div class="link_my_fan_books" data-toggle='modal' data-target='#getMyFanBooks'>Мои книги</div>
                    <img class='small-hidden book_image_big' src='<?php echo base_url()?>uploads/icons/fan_book.png' data-toggle='modal' data-target='#getMyFanBooks'>
                    <div class="centered">
                        <div class="suggest_btn link_my_fan_books" data-toggle="modal" data-target="#insertBookSuggestion">
                            <div>
                                <img class="small-hidden" src="<?php echo base_url()?>uploads/icons/plus.png">
                            </div>
                            Предложить книгу
                        </div>
                    </div>
                </div>
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    <div class="book_categories small-hidden">
                        Выберите категории книг
                    </div>
                    <div id="showMobileCategories" class="book_categories huge-hidden big-hidden middle-hidden">
                        Категории книг
                    </div>
                    <div id="mobileCategories" class="row small-hidden all_categories">
                        <form action="javascript:void(0)" onchange="chooseBooksByCategories(this)">
                            <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                            <?php
                            foreach ($book_categories as $book_category) {
                                echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-6'>
                                    <input type='checkbox' id='check_$book_category->id' name='category_ids[]' value='$book_category->id' />
                                    <label for='check_$book_category->id'><span></span>$book_category->category_name</label>
                                </div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="all_books">
                <h3 class="centered">Все книги</h3>
                <?php echo $books?>
            </div>
        </div>
        <div class="pos_recommendations small-hidden middle-hidden big-hidden col-xs-3">
            <div class="book_actions">
                <h5 class="centered">Действия друзей</h5>
                <?php
                foreach($book_actions as $book_action) {
                    echo "<div class='action-info'>
                    <img class='action-image' src='" . base_url() . "uploads/images/book_images/$book_action->book_image'>
                    <span class='action-text'>
                        $book_action->book_action <br>
                        <a href='" . base_url() . "one_book/$book_action->book_id'>Смотреть</a>
                    </span><hr>
                </div>";
                };

                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer');?>

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

<div class="modal fade" id="getMyFanBooks" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Книги, которые Вы добавили в любимки</h4>
            </div>
            <div class="modal-body row">
                <?php

                foreach ($my_fan_books as $my_fan_book) {
                    echo "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$my_fan_book->book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$my_fan_book->book_image'>
                        </div>
                        <div class='book_name'>$my_fan_book->book_name</div>
                    </a>
                </div>";
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertBookSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Предложение новой книги Вами</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="javascript:void(0)" onsubmit="insertBookSuggestion(this)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <label>Название книги</label>
                    <input required type="text" class="form-control" name="book_name">
                    <label>Файл книги в PDF-формате</label>
                    <input required type="file" class="form-control" name="book_file">
                    <label>Автор</label>
                    <input required type="text" class="form-control" name="book_author">
                    <label>Описание</label>
                    <textarea required rows="5" class="form-control" name="book_description"></textarea>
                    <label>Обложка</label>
                    <input required type="file" class="form-control" name="book_image">
                    <label>Категория</label>
                    <select required class="btn btn-warning" name="category_id">
                        <option selected value="">Выберите категорию</option>
                        <?php
                        foreach($book_categories as $book_category) {
                            echo "<option value='$book_category->id'>$book_category->category_name</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-success center-block">Отправить предложение</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url()?>js/common.js"></script>
<script>

    function insertBookSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_suggestions/insert_book_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.book_file_error) {
                alert(message.book_file_error);
            }
            if (message.book_image_error) {
                alert(message.book_image_error);
            }
            if (message.success_suggestion) {
                alert(message.success_suggestion);
                $("#insertBookSuggestion").modal('hide');
            }
        })
    }

    window.onblur = function () {console.log('неактивен')};
    window.onfocus = function () {console.log('снова активен')};

    var offset = 0;
    $(document).scroll(function() {
        if($(document).scrollTop() >= $(document).height() - $(window).height()) {
            offset = offset + 12;
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>books/index",
                data: {offset: offset, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $('.csrf').val(message.csrf_hash);
                $("#all_books").append(message.books);
            })
        }
    });

    function chooseBooksByCategories(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_categories/index",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_books").html(message.books_by_categories);
        })
    }

    function getBookEmotions(context) {
        var book_id = context.getAttribute('data-book_id');
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
        var book_id = context.getAttribute('data-book_id');
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

    function getMyFanBooks(context) {
        var book_id = context.getAttribute('data-book_id');
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

    function putEmotionOrFan() {
        alert('Чтобы поставить эмоцию на книгу или добавить книгу в любимки, войдите в неё!');
    }

    $("#getBookEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getBookEmotions").modal('hide');
        };
    });
    $("#getBookFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getBookFans").modal('hide');
        };
    });

    $("#getMyFanBooks").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyFanBooks").modal('hide');
        };
    });

    $("#insertBookSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertBookSuggestion").modal('hide');
        };
    });
</script>


</body>
</html>