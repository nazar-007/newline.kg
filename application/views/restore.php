<!DOCTYPE html>
<html>
<head>
    <title>Восстановление аккаунта</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
</head>
<body>
<div class="container">
    <div class="col-md-12 col-lg-6" id="restore">
        <img class="img-thumbnail newline-huge huge-hidden" src="<?php echo base_url()?>uploads/icons/newline.png">
        <h3 class="centered">Восстановление аккаунта</h3>
        <form method="post" onsubmit="checkEmail(this)" action="javascript:void(0)">
            <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
            <div class="form-group">
                <label>Введите Ваш E-mail:</label>
                <input type="text" class="form-control" name="email">
            </div>
            <button id="check_email_button" type="submit" class="btn btn-primary center-block">Следующий шаг</button>
        </form>
    </div>
    <div class="col-lg-6 hedgehog small-hidden middle-hidden big-hidden">
        <img class="hedgehog-logo" src="<?php echo base_url()?>uploads/icons/logo.png"><br>
        <img class="newline-logo" src="<?php echo base_url()?>uploads/icons/newline.png"><br>
    </div>
</div>

<script>

    function checkEmail(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "users/check_email",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            if (message.email_error) {
                alert(message.email_error);
            }
            if (message.email_success) {
                $("#check_email_button").remove();
                $("#restore").append("<form method='post' action='javascript:void(0)' onsubmit='checkSecretAnswers(this)'>" +
                    "<input type='hidden' class='csrf' name='csrf_test_name' value='" + message.csrf_hash + "'>" +
                    "<input type='hidden' name='email' value='" + message.email + "' " +
                    "<div>1 вопрос: " + message.first_secret_question + "</div>" +
                    "<div class='form-group'><label>Ответ на 1 вопрос: </label>" +
                    "<input required type='text' class='form-control' name='first_secret_answer'></div>" +
                    "<div>2 вопрос: " + message.second_secret_question + "</div>" +
                    "<div class='form-group'><label>Ответ на 2 вопрос: </label>" +
                    "<input required type='text' class='form-control' name='second_secret_answer'></div>" +
                    "<button id='check_secret_answers' type='submit' class='btn btn-primary center-block'>Следующий шаг</button>" +
                    "</form>"
                );
            }
        })
    }

    function checkSecretAnswers(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "users/check_secret_answers",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            if (message.answers_error) {
                alert(message.answers_error);
            }
            if (message.answers_success) {
                $("#check_secret_answers").remove();
                $("#restore").append("<form onsubmit='setNewPassword(this)' action='javascript:void(0)'>" +
                    "<input class='csrf' type='hidden' name='csrf_test_name' value='" + message.csrf_hash + "'>" +
                    "<input readonly type='hidden' name='email' value='" + message.email + "'> " +
                    "<div class='form-group'>" +
                        "<label>Введите новый пароль (не менее 6 символов):</label>" +
                        "<input required class='form-control' type='password' name='new_password'>" +
                    "</div>" +
                    "<button id='checkSecretAnswers' type='submit' class='btn btn-primary center-block'>ВОССТАНОВИТЬ</button>" +
                    "</form>");
            }
        })
    }


    function setNewPassword(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "users/set_new_password",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            if (message.password_error) {
                alert(message.password_error);
            }
            if (message.password_success) {
                window.location.href = "<?php echo base_url()?>publications";
            }
        })
    }

    window.onload = function () {
        document.getElementsByClassName('newline-logo')[0].classList.add('logo-animated');
    };
</script>
</body>
</html>