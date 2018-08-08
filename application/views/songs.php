<!DOCTYPE html>
<html>
<head>
    <title>Песни</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/songs.css">
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
        <div class="pos_songs col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="row">
                <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>
                    <div class="link_my_fan_songs" data-toggle='modal' data-target='#getMyFanSongs'>Мои любимые песни <?php if ($total_songs > 0) { echo '(' .  $total_songs . ')';}?></div>
                    <img class='small-hidden song_image_big' src='<?php echo base_url()?>uploads/icons/fan_song.png' data-toggle='modal' data-target='#getMyFanSongs'>
                    <div class="centered">
                        <div class="suggest_btn link_my_fan_songs" data-toggle="modal" data-target="#insertSongSuggestion">
                            <div>
                                <img class="small-hidden" src="<?php echo base_url()?>uploads/icons/plus.png">
                            </div>
                            Предложить песню
                        </div>
                    </div>
                </div>
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    <div class="song_categories small-hidden">
                        Выберите категории песен
                    </div>
                    <div id="showMobileCategories" class="song_categories huge-hidden big-hidden middle-hidden">
                        Категории песен
                    </div>
                    <div id="mobileCategories" class="row small-hidden all_categories">
                        <form action="javascript:void(0)" onchange="chooseSongsByCategories(this)">
                            <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                            <?php
                            foreach ($song_categories as $song_category) {
                                echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-6'>
                                    <input type='checkbox' class='checkbox' id='check_$song_category->id' name='category_ids[]' value='$song_category->id' />
                                    <label for='check_$song_category->id'><span></span>$song_category->category_name</label>
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
                    <input type="text" class="form-control search_by_name_input" name="search_by_name" placeholder="Поиск по названию песни или исполнителю">
                </form>
            </div>
            <div class="row" id="all_songs">
                <h3 class="centered">Все песни</h3>
                <?php echo $songs?>
            </div>
        </div>
        <div class="pos_recommendations small-hidden middle-hidden big-hidden col-xs-3">
            <div class="song_actions">
                <h5 class="centered">Действия друзей</h5>
                <?php
                if (count($friend_ids) == 0 || count($song_actions) == 0) {
                    echo "<h4 class='centered'>Действий с песнями от Ваших друзей пока нет.</h4>";
                } else {
                    foreach ($song_actions as $song_action) {
                        echo "<div class='action-info'>
                    <span class='action-text'>
                        $song_action->song_action <br>
                        <a href='" . base_url() . "one_song/$song_action->song_id'>Смотреть</a>
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

<div class="modal fade" id="getSongEmotions" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, поставившие эмоции на песню</h4>
            </div>
            <div id="one_song_emotions" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getSongFans" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Люди, добавившие песню в любимки</h4>
            </div>
            <div id="one_song_fans" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getMyFanSongs" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Песни, которые Вы добавили в любимки</h4>
            </div>
            <div class="modal-body">
                <?php

                if (count($my_fan_songs) == 0) {
                    echo "<h4 class='centered'>Пока Вы не добавили ни одной песни.</h4>";
                } else {
                    foreach ($my_fan_songs as $my_fan_song) {
                        echo "<div><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png'>
                        </span>
                    </div></div>
                    <div class='song-name'>
                        <a href='" . base_url() . "one_song/$my_fan_song->song_id'>
                            $my_fan_song->song_singer - $my_fan_song->song_name
                        </a>
                    </div>
                    <audio class='player' src='" . base_url() . "uploads/song_files/$my_fan_song->song_file' controls controlsList='nodownload'></audio>";
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

<div class="modal fade" id="insertSongSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Предложение новой песни Вами</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="javascript:void(0)" onsubmit="insertSongSuggestion(this)" enctype="multipart/form-data">
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <label>Название песни</label>
                    <input required type="text" class="form-control" name="song_name">
                    <label>Файл песни в MP3-формате</label>
                    <input required type="file" class="form-control" name="song_file">
                    <label>Певец/группа</label>
                    <input required type="text" class="form-control" name="song_singer">
                    <label>Текст песни</label>
                    <textarea required rows="5" class="form-control" name="song_lyrics"></textarea>
                    <label>Категория</label>
                    <select required class="btn btn-warning" name="category_id">
                        <option selected value="">Выберите категорию</option>
                        <?php
                        foreach($song_categories as $song_category) {
                            echo "<option value='$song_category->id'>$song_category->category_name</option>";
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

    function insertSongSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_suggestions/insert_song_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.song_file_error) {
                alert(message.song_file_error);
            }
            if (message.success_suggestion) {
                $(".form-control").val('');
                alert(message.success_suggestion);
                $("#insertSongSuggestion").modal('hide');
            }
        })
    }
    function playSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.play();
    }
    function pauseSong(context) {
        context.parentElement.parentElement.nextElementSibling.nextElementSibling.pause();
    }
    var player = $('.player');
    player.on('play', function() {
        player.not(this).each(function() {
            this.pause();
        })
    });

    var offset = 0;
    $(document).scroll(function() {
        if(($(document).scrollTop() + 1) >= $(document).height() - $(window).height()) {
            offset = offset + 12;
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>songs/index",
                data: {offset: offset, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $('.csrf').val(message.csrf_hash);
                $("#all_songs").append(message.songs);
            })
        }
        var player = $('.player');
        player.off().on('play', function() {
            player.not(this).each(function() {
                this.pause();
            })
        });
    });

    function chooseSongsByCategories(context) {
        offset = 0;
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_categories/index",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $(".form-control").val('');
            $("#all_songs").html(message.songs_by_categories);
        })
    }

    function searchByName(context) {
        offset = 0;
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>songs/search_songs",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_songs").html(message.search_songs);
            var checkbox = document.getElementsByClassName('checkbox');
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked) {
                    checkbox[i].checked = false;
                }
            }
        })
    }
    function getSongEmotions(context) {
        var song_id = context.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_emotions/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_emotions").html(message.one_song_emotions);
        })
    }
    function getSongFans(context) {
        var song_id = context.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_fans").html(message.one_song_fans);
        })
    }
    function getMyFanSongs(context) {
        var song_id = context.getAttribute('data-song_id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_fans/index",
            data: {song_id: song_id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#one_song_fans").html(message.one_song_fans);
        })
    }
    function putEmotionOrFan() {
        alert('Чтобы поставить эмоцию на песню или добавить песню в любимки, войдите в неё!');
    }

    $("#getSongEmotions").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getSongEmotions").modal('hide');
        };
    });
    $("#getSongFans").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getSongFans").modal('hide');
        };
    });

    $("#getMyFanSongs").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyFanSongs").modal('hide');
        };
    });

    $("#insertSongSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertSongSuggestion").modal('hide');
        };
    });
</script>


</body>
</html>