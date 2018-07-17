<!DOCTYPE html>
<html>
<head>
    <title>Подарки</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/gift_stakes.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_gifts">
            <div class="row">
                <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3 my_gifts' data-toggle='modal' data-target='#getMyGifts'>
                    <div class="link_my_gifts">Мои подарки</div><br>
                    <img class='small-hidden gift_image_big' src='<?php echo base_url()?>uploads/icons/big_gift.png'>
                </div>
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    <div class="gift_categories small-hidden">
                        Выберите категории подарков
                    </div>
                    <div id="showMobileCategories" class="gift_categories huge-hidden big-hidden middle-hidden">
                        Категории подарков
                    </div>
                    <div id="mobileCategories" class="row small-hidden all_categories">
                        <form action="javascript:void(0)" onchange="chooseGiftsByCategories(this)">
                            <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                            <?php
                            foreach ($gift_categories as $gift_category) {
                                echo "<div class='col-lg-3 col-sm-6 col-md-4 col-xs-6'>
                                    <input type='checkbox' id='check_$gift_category->id' name='category_ids[]' value='$gift_category->id' />
                                    <label for='check_$gift_category->id'><span></span>$gift_category->category_name</label>
                                </div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="all_gifts">
                <h3 class="centered">Все подарки</h3>
                <?php
                    foreach ($gifts as $gift) {
                        echo "<div class='col-xs-6 col-sm-4 col-lg-3 one_gift'>
                        <span onclick='insertGiftSentPress(this)' data-toggle='modal' data-target='#insertGiftSent' data-id='$gift->id' data-gift_name='$gift->gift_name'>
                            <div class='gift_image'>
                                <img src='" . base_url() . "uploads/images/gift_images/$gift->gift_file'>
                            </div>
                            <div class='gift_name'>
                                $gift->gift_name
                            </div>";
                        if ($gift->gift_price != 0) {
                            echo "<div class='badge gift_price'>$gift->gift_price сом</div>";
                        } else {
                            echo "<div class='badge gift_price' style='background-color: orange'>Бесплатно!</div>";
                        }
                        echo "</span>
                    </div>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertGiftSent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Отправка подарка пользователю</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertGiftSent(this)">
                    <div class="form-group">
                        <input type="hidden" name="gift_id" class="insert_id">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input type="hidden" name="user_id" class="user_id">
                        <h3 class="insert_name centered"></h3>
                        <div class="row">
                        <?php
                        foreach ($friends as $friend) {
                            echo "<div onclick='chooseUserId(this)' data-user_id='$friend->friend_id' class='col-xs-6 col-sm-4 col-lg-3 friend_$friend->friend_id friend centered'>
                                    <div class='friend_user_image'>
                                        <img src='uploads/images/user_images/$friend->main_image' class='friend_avatar'>
                                    </div>
                                    <div class='friend_name'>
                                        $friend->nickname $friend->surname
                                    </div>
                                </div>";
                        }
                        ?>
                        </div>
                        <br/>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, отправить</button>
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

<div class="modal fade" id="getMyGifts" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр моих подарков</h4>
            </div>
            <div class="modal-body">
                <div class="all_my_gifts">
                <?php

                if (count($my_gifts) == 0) {
                    echo "<div class='centered'>Вам ещё никто ничего не подарил!</div>";
                } else {
                    foreach ($my_gifts as $my_gift) {
                        echo "<div class='centered one_my_gift_$my_gift->id my_gift'>
                        <div class='gift_date'>
                            $my_gift->sent_date, $my_gift->sent_time 
                            <div class='delete_my_gift_$my_gift->id right cross'>
                                <div onclick='deleteMyGiftPress(this)' data-id='$my_gift->id'>X</div>
                            </div>
                        </div>
                        <img src='" . base_url() . "uploads/images/gift_images/$my_gift->gift_file'>
                        <div class='sent_gift_name'>
                            $my_gift->gift_name
                        </div>
                        <div class='sent_user_name'>
                            от <a href='" . base_url() . "one_user/$my_gift->email'>$my_gift->nickname $my_gift->surname</a>
                        </div>
                    </div>";
                    }
                    }
                ?>
                </div>
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


    function returnCross(context) {
        var id = context.getAttribute('data-id');
        $('.delete_my_gift_' + id).html("<div onclick='deleteMyGiftPress(this)' data-id='" + id + "'>X</div>");
    }

    function deleteMyGiftPress(context) {
        var id = context.getAttribute('data-id');
        $('.delete_my_gift_' + id).html("Удалить подарок?<br><button class='btn btn-danger' onclick='deleteMyGift(this)' data-id='" + id + "'>Да</button><button class='btn btn-success' onclick='returnCross(this)' data-id='" + id + "'>Нет</button>");
    }

    function deleteMyGift(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>gift_sent/delete_gift_sent",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.gift_sent_error) {
                alert(message.gift_sent_error);
            } else {
                $('.one_my_gift_' + id).remove();
                alert(message.gift_sent_success);
            }
        })
    }

    function chooseGiftsByCategories(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "gift_categories/index",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_gifts").html(message.gifts_by_categories);
        })
    }


    function insertGiftSentPress(context) {
        var id = context.getAttribute('data-id');
        var gift_name = context.getAttribute('data-gift_name');
        $('.insert_id').val(id);
        $('.insert_name').html('Выберите друга, чтобы отправить ему подарок ' + gift_name);
        $('.friend').removeClass('friend_pressed');
        $('.user_id').val('');
    }

    function insertGiftSent(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "gift_sent/insert_gift_sent",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.gift_sent_error) {
                alert(message.gift_sent_error);
            }
            if (message.gift_sent_success) {
                $("#insertGiftSent").trigger('click');
                $('.friend').removeClass('friend_pressed');
                $('.user_id').val('');
                alert(message.gift_sent_success);
            }
        })
    }
    function chooseUserId(context) {
        var user_id = context.getAttribute('data-user_id');
        $('.user_id').val(user_id);
        $('.friend').removeClass('friend_pressed');
        $('.friend_' + user_id).eq(0).addClass('friend_pressed');
    }

    $("#insertGiftSent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertGiftSent").modal('hide');
        };
    });

    $("#getMyGifts").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyGifts").modal('hide');
        };
    });
</script>

</body>
</html>