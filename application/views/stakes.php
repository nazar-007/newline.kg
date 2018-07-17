<!DOCTYPE html>
<html>
<head>
    <title>Награды</title>
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
        <div class="pos_stakes">
            <div class="row">
                <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3 my_stakes' data-toggle='modal' data-target='#getMyStakes'>
                    <div class="link_my_stakes">Мои награды</div><br>
                    <img class='small-hidden stake_image_big' src='<?php echo base_url()?>uploads/icons/big_stake.png'>
                </div>
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    <div class="stake_categories small-hidden">
                        Выберите категории наград
                    </div>
                    <div id="showMobileCategories" class="stake_categories huge-hidden big-hidden middle-hidden">
                        Категории наград
                    </div>
                    <div id="mobileCategories" class="row small-hidden all_categories">
                        <form action="javascript:void(0)" onchange="chooseStakesByCategories(this)">
                            <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                            <?php
                            foreach ($stake_categories as $stake_category) {
                                echo "<div class='col-lg-3 col-sm-6 col-md-6 col-xs-6'>
                                    <input type='checkbox' id='check_$stake_category->id' name='category_ids[]' value='$stake_category->id' />
                                    <label for='check_$stake_category->id'><span></span>$stake_category->category_name</label>
                                </div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" id="all_stakes">
                <h3 class="centered">Все награды</h3>
                <?php
                    foreach ($stakes as $stake) {
                        echo "<div class='col-xs-6 col-sm-4 col-lg-3 one_stake'>
                        <span onclick='insertStakeFanPress(this)' data-toggle='modal' data-target='#insertStakeFan' data-id='$stake->id' data-stake_name='$stake->stake_name'>
                            <div class='stake_image'>
                                <img src='" . base_url() . "uploads/images/stake_images/$stake->stake_file'>
                            </div>
                            <div class='stake_name'>
                                $stake->stake_name
                            </div>";
                        if ($stake->stake_price != 0) {
                            echo "<div class='badge stake_price'>$stake->stake_price сом</div>";
                        } else {
                            echo "<div class='badge stake_price' style='background-color: orange'>Бесплатно!</div>";
                        }
                        echo "</span>
                    </div>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="insertStakeFan" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Заказ награды самому себе</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="insertStakeFan(this)">
                    <div class="form-group">
                        <input type="hidden" name="stake_id" class="insert_id">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <h3 class="insert_name centered"></h3>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, получить</button>
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

<div class="modal fade" id="getMyStakes" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр моих наград</h4>
            </div>
            <div class="modal-body">
                <div class="all_my_stakes">
                <?php

                if (count($my_stakes) == 0) {
                    echo "<div class='centered'>Вы ещё не получили ни одной награды! Заказывайте быстрее!</div>";
                } else {
                    foreach ($my_stakes as $my_stake) {
                        echo "<div class='centered one_my_stake_$my_stake->id my_stake'>
                        <div class='stake_date'>
                            $my_stake->stake_date, $my_stake->stake_time 
                            <div class='delete_my_stake_$my_stake->id right cross'>
                                <div onclick='deleteMyStakePress(this)' data-id='$my_stake->id'>X</div>
                            </div>
                        </div>
                        <img src='" . base_url() . "uploads/images/stake_images/$my_stake->stake_file'>
                        <div class='sent_gift_name'>
                            $my_stake->stake_name
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
        $('.delete_my_stake_' + id).html("<div onclick='deleteMyStakePress(this)' data-id='" + id + "'>X</div>");
    }

    function deleteMyStakePress(context) {
        var id = context.getAttribute('data-id');
        $('.delete_my_stake_' + id).html("Удалить награду?<br><button class='btn btn-danger' onclick='deleteMyStake(this)' data-id='" + id + "'>Да</button><button class='btn btn-success' onclick='returnCross(this)' data-id='" + id + "'>Нет</button>");
    }

    function deleteMyStake(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>stake_fans/delete_stake_fan",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.stake_fan_error) {
                alert(message.stake_fan_error);
            } else {
                $('.one_my_stake_' + id).remove();
                alert(message.stake_fan_success);
            }
        })
    }

    function chooseStakesByCategories(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "stake_categories/index",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#all_stakes").html(message.stakes_by_categories);
        })
    }


    function insertStakeFanPress(context) {
        var id = context.getAttribute('data-id');
        var stake_name = context.getAttribute('data-stake_name');
        $('.insert_id').val(id);
        $('.insert_name').html('Вы действительно хотите заказать себе награду ' + stake_name + '?');
    }

    function insertStakeFan(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "stake_fans/insert_stake_fan",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.stake_fan_error) {
                alert(message.stake_fan_error);
            }
            if (message.stake_fan_success) {
                $("#insertStakeFan").trigger('click');
                alert(message.stake_fan_success);
            }
        })
    }

    $("#insertStakeFan").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#insertStakeFan").modal('hide');
        };
    });

    $("#getMyStakes").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getMyStakes").modal('hide');
        };
    });
</script>

</body>
</html>