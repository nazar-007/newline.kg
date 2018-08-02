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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
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
                    <div class="link_my_fan_events" data-toggle='modal' data-target='#getMyFanEvents'>Мои любимые события <?php if ($total_events > 0) { echo '(' .  $total_events . ')';}?></div>
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
                    <div class="event_categories small-hidden">
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
                                    <input type='checkbox' class='checkbox' id='check_$event_category->id' name='category_ids[]' value='$event_category->id' />
                                    <label for='check_$event_category->id'><span></span>$event_category->category_name</label>
                                </div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div>
                <form method="post" action="javascript:void(0)" onkeyup="searchByName(this)">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <input type="text" class="form-control search_by_name_input" name="search_by_name" placeholder="Поиск по названию события">
                </form>
            </div>
            <div class="row events" id="all_events">
                <h3 class="centered">Все события</h3>
                <?php echo $events?>
            </div>
        </div>
        <div class="pos_recommendations small-hidden middle-hidden big-hidden col-xs-3">
            <div class="event_actions">
                <h5 class="centered">Действия друзей</h5>
                <?php
                if (count($friend_ids) == 0 || count($event_actions) == 0) {
                    echo "<h4 class='centered'>Действий с событиями от Ваших друзей пока нет.</h4>";
                } else {
                    foreach($event_actions as $event_action) {
                        echo "<div class='action-info'>
                        <span class='action-text'>
                            $event_action->event_action <br>
                            <a href='" . base_url() . "one_event/$event_action->event_id'>Смотреть</a>
                        </span><hr>
                    </div>";
                    }
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
                if (count($my_fan_events) == 0) {
                    echo "<h4 class='centered'>Пока Вы не добавили ни одного события.</h4>";
                } else {
                    foreach ($my_fan_events as $my_fan_event) {
                        $event_id = $my_fan_event->event_id;
                        $event_name = $my_fan_event->event_name;
                        $event_date = $my_fan_event->event_start_date;
                        $day = $event_date[0] . $event_date[1];
                        $year = $event_date[6] . $event_date[7] . $event_date[8] . $event_date[9];
                        if ($event_date[3] == '0') {
                            $month_index = $event_date[4];
                        } else {
                            $month_index = $event_date[3] . $event_date[4];
                        }
                        $months_array = array(
                            "1" => "Января", "2" => "Февраля", "3" => "Марта",
                            "4" => "Апреля", "5" => "Мая", "6" => "Июня",
                            "7" => "Июля", "8" => "Августа", "9" => "Сентября",
                            "10" => "Октября", "11" => "Ноября", "12" => "Декабря"
                        );
                        $month = $months_array[$month_index];
                        echo "<div class='list col-xs-6 col-sm-6 col-md-6 col-lg-6 event'>
                        <div class='centered'>
                        <div class='event-date'>
                        <a href='" . base_url() . "one_event/$event_id'>
                            <div class='date'>
                                $day
                            </div>
                            <br>
                            <div class='date'>
                                $month
                            </div>
                            <br>
                            <div class='date'>
                                $year
                            </div>
                        </a>
                        </div>
                        <div class='event-name'>
                            $event_name
                        </div>
                    </div>
                    </div>";
                    }
                }
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
                    <input required type="text" class="form-control" name="event_address">
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
                                document.write("<option value='" + (i+1) + "'>" + months[i] + "</option>");
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
                                document.write("<option>" + i + "</option>");
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

    window.onblur = function () {console.log('неактивен')};
    window.onfocus = function () {console.log('снова активен')};

    var offset = 0;
    $(document).scroll(function() {
        if($(document).scrollTop() >= $(document).height() - $(window).height()) {
            offset = offset + 12;
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>events/index",
                data: {offset: offset, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $('.csrf').val(message.csrf_hash);
                $("#all_events").append(message.events);
            })
        }
    });

    function chooseEventsByCategories(context) {
        offset = 0;
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
            $(".form-control").val('');
            $("#all_events").html(message.events_by_categories);
        })
    }
    function insertEventSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_suggestions/insert_event_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.success_suggestion) {
                alert(message.success_suggestion);
                $("#insertEventSuggestion").modal('hide');
                $(".form-control").val('');
            }
        })
    }
    function searchByName(context) {
        offset = 0;
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>events/search_events",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_events").html(message.search_events);

            var checkbox = document.getElementsByClassName('checkbox');
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked) {
                    checkbox[i].checked = false;
                }
            }
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
        alert('Чтобы поставить эмоцию на событие или добавить событие в любимки, войдите в событие!');
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