<!DOCTYPE html>
<html>
<head>
    <title>События</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/events.css">
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
        <div class="pos_events col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="row">
                <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
                    <div class="link_my_fan_events" data-toggle='modal' data-target='#getMyFanEvents'>Мои события</div>
                    <img class='small-hidden event_image_big' src='<?php echo base_url()?>uploads/icons/big_event.png' data-toggle='modal' data-target='#getMyFanEvents'>
                    <div class="centered">
                        <div class="suggest_btn link_my_fan_events" data-toggle="modal" data-target="#insertEventSuggestion">
                            <div>
                                <img class="small-hidden" src="<?php echo base_url()?>uploads/icons/plus.png">
                            </div>
                            Предложить событие
                        </div>
                    </div>
                </div>
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    <div class="book_events small-hidden">
                        Выберите категории событий
                    </div>
                    <div id="showMobileCategories" class="event_categories huge-hidden big-hidden middle-hidden">
                        Категории событий
                    </div>
                    <div id="mobileCategories" class="row small-hidden all_categories">
                        <form action="javascript:void(0)" onchange="chooseEventsByCategories(this)">
                            <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                            <?php
                            foreach ($event_categories as $event_category) {
                                echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-6'>
                                    <input type='checkbox' id='check_$event_category->id' name='category_ids[]' value='$event_category->id' />
                                    <label for='check_$event_category->id'><span></span>$event_category->category_name</label>
                                </div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="all_events">
                <h3 class="centered">Все события</h3>
                <?php echo $events?>
            </div>
        </div>
        <div class="pos_recommendations small-hidden middle-hidden big-hidden col-xs-3">
            <div class="book_actions">
                <h5 class="centered">Действия друзей</h5>
                <?php
                foreach($event_actions as $event_action) {
                    echo "<div class='action-info'>
                    <span class='action-text'>
                        $event_action->event_action <br>
                        <a href='" . base_url() . "one_event/$event_action->event_id'>Смотреть</a>
                    </span><hr>
                </div>";
                };

                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer');?>

<div class="modal fade" id="getEventEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на событие</h4>
            </div>
            <div id="one_event_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getEventFans" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, которые пойдут на данное событие</h4>
            </div>
            <div id="one_event_fans" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getMyFanEvents" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">События, на которые пойдёте Вы</h4>
            </div>
            <div class="modal-body row">
                <?php
//                foreach ($my_fan_books as $my_fan_book) {
//                    echo "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
//                    <a href='" . base_url() . "one_book/$my_fan_book->book_id'>
//                        <div class='book_cover'>
//                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$my_fan_book->book_image'>
//                        </div>
//                        <div class='book_name'>$my_fan_book->book_name</div>
//                    </a>
//                </div>";
//                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertEventSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Предложение нового события Вами</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="javascript:void(0)" onsubmit="insertEventSuggestion(this)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <label>Название события</label>
                    <input required type="text" class="form-control" name="event_name">
                    <label>Описание</label>
                    <textarea required rows="5" class="form-control" name="event_description"></textarea>
                    <label>Адрес</label>
                    <input required type="text" class="form-control" name="book_author">
                    <label>Дата начала события:</label><br>
                    <select required name="day">
                        <option value="">День</option>
                        <script>
                            for (var i = 1; i <= 31; i++) {
                                document.write("<option>" + i + "</option>");
                            }
                        </script>
                    </select>
                    <select name="month">
                        <option value="">Месяц</option>
                        <script>
                            var months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
                            for (var i = 0; i < months.length; i++) {
                                document.write("<option>" + months[i] + "</option>");
                            }
                        </script>
                    </select>
                    <select name="year">
                        <option value="">Year</option>
                        <script>
                            for (var i = 2018; i <= 2118; i++) {
                                document.write("<option>" + i + "</option>");
                            }
                        </script>
                    </select><br>
                    <label>Время начала события:</label><br>
                    <select name="hour">
                        <option value="">Час</option>
                        <script>
                            for (var i = 0; i < 24; i++) {
                                document.write("<option>" + i + "</option>");
                            }
                        </script>
                    </select>
                    <select name="minute">
                        <option value="">Минута</option>
                        <script>
                            for (var i = 0; i < 60; i++) {
                                document.write("<option>" + months[i] + "</option>");
                            }
                        </script>
                    </select><br>
                    <label>Категория</label><br>
                    <select required class="btn btn-warning" name="category_id">
                        <option selected value="">Выберите категорию</option>
                        <?php
                        foreach($event_categories as $event_category) {
                            echo "<option value='$event_category->id'>$event_category->category_name</option>";
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

    function insertEventSuggestion(context) {
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
            if (message.event_date_error) {
                alert(message.event_date_error);
            }
            if (message.event_time_error) {
                alert(message.event_time_error);
            }
            if (message.success_suggestion) {
                alert(message.success_suggestion);
                $("#insertEventSuggestion").modal('hide');
            }
        })
    }

    window.onblur = function () {console.log('неактивен')};
    window.onfocus = function () {console.log('снова активен')};

//    var offset = 0;
//    $(document).scroll(function() {
//        if($(document).scrollTop() >= $(document).height() - $(window).height()) {
//            offset = offset + 12;
//            $.ajax({
//                method: "POST",
//                url: "<?php //echo base_url()?>//books/index",
//                data: {offset: offset, csrf_test_name: $(".csrf").val()},
//                dataType: "JSON"
//            }).done(function (message) {
//                $('.csrf').val(message.csrf_hash);
//                $("#all_books").append(message.books);
//            })
//        }
//    });

    function chooseEventsByCategories(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_categories/index",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_events").html(message.events_by_categories);
        })
    }

    function getEventEmotions(context) {
        var event_id = context.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_emotions/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_emotions").html(message.one_event_emotions);
        })
    }
    function getEventFans(context) {
        var event_id = context.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_fans").html(message.one_event_fans);
        })
    }

    function getMyFanEvents(context) {
        var event_id = context.getAttribute('data-event_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_fans/index",
            data: {event_id: event_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_event_fans").html(message.one_event_fans);
        })
    }

    function putEmotionOrFan() {
        alert('Чтобы поставить эмоцию на событие или добавить событие в любимки, войдите в неё!');
    }

    $("#getEventEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getEventEmotions").modal('hide');
        };
    });
    $("#getEventFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getEventFans").modal('hide');
        };
    });

    $("#getMyFanEvents").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyFanEvents").modal('hide');
        };
    });

    $("#insertEventSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertEventSuggestion").modal('hide');
        };
    });
</script>


</body>
</html>