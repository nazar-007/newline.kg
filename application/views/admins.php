<!DOCTYPE html>
<html>
<head>
    <title>New Line kg</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/admins.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
</head>
<body>
<div class="container">
    <div class="col-md-12 col-lg-6">
        <img class="img-thumbnail newline-huge huge-hidden" src="<?php echo base_url()?>uploads/icons/newline.png">
                <form method="post" action="<?php echo base_url()?>admins/authorization_admin">
                    <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="text" class="form-control" placeholder="Введите email" name="admin_email">
                    </div>
                    <div class="form-group">
                        <label>Пароль:</label>
                        <input type="password" class="form-control" placeholder="Введите пароль" name="admin_password">
                    </div>
                    <button type="submit" class="btn btn-primary center-block">Войти</button>
                </form>
            </div>
    <div class="col-lg-6 hedgehog small-hidden middle-hidden big-hidden">
        <img class="hedgehog-logo" src="<?php echo base_url()?>uploads/icons/logo.png"><br>
        <img class="newline-logo" src="<?php echo base_url()?>uploads/icons/newline.png"><br>
        <img class="admins" src="<?php echo base_url()?>uploads/icons/admins.png"><br>
    </div>
</div>

<script>
    window.onload = function () {
        document.getElementsByClassName('newline-logo')[0].classList.add('logo-animated');
        document.getElementsByClassName('admins')[0].classList.add('admins-animated');
    };
</script>
</body>
</html>