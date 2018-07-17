<!DOCTYPE html>
<html>
<head>
    <title>Мои гости</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/guests.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-sm-3 col-md-9 col-lg-9">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_guests col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="row" id="all_guests">
                <h3 class="centered">Все гости за неделю</h3>
                <?php
                if (count($guests) == 0) {
                    echo "За последнюю неделю никто не посещал Вашу страницу";
                }
                foreach ($guests as $guest) {
                    echo "<div class='col-xs-6 col-sm-4 col-lg-3 centered guest'>
                        <a href='" . base_url() . "one_user/$guest->email'>
                            <div class='guest_user_image'>
                                <img src='uploads/images/user_images/$guest->main_image' class='guest_avatar'>
                            </div>
                            <div class='guest_name'>
                                $guest->nickname $guest->surname
                            </div>
                        </a>
                        <div class='guest_date'>
                            $guest->guest_date, $guest->guest_time
                        </div>
                    </div>";
                }
                ?>
            </div>
            <div class="row" id="all_guests">
                <h3 class="centered">У кого я был в гостях за последнюю неделю</h3>
                <?php
                if (count($users) == 0) {
                    echo "За последнюю неделю никто не посещал Вашу страницу";
                }
                foreach ($users as $user) {
                    echo "<div class='col-xs-6 col-sm-4 col-lg-3 centered guest'>
                        <a href='" . base_url() . "one_user/$user->email'>
                            <div class='guest_user_image'>
                                <img src='uploads/images/user_images/$user->main_image' class='guest_avatar'>
                            </div>
                            <div class='guest_name'>
                                $user->nickname $user->surname
                            </div>
                        </a>
                        <div class='guest_date'>
                            $user->guest_date, $user->guest_time
                        </div>
                    </div>";
                }
                ?>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view("footer");?>


<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>

</script>

</body>
</html>