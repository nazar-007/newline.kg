<!DOCTYPE html>
<html>
<head>
    <title>Книги</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-5.1.0-web/css/fontawesome.css">
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/all.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
</head>
<body>
<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3">
            <ul class="catalog">
                <li>
                    <a href="<?php echo base_url()?>">
                        <img src="<?php echo base_url()?>uploads/icons/internet.png">
                        Моя страница
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>friends">
                        <img src="<?php echo base_url()?>uploads/icons/notebook.png">
                        Друзья
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>guests">
                        <img src="<?php echo base_url()?>uploads/icons/notebook.png">
                        Гости
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>books">
                        <img src="<?php echo base_url()?>uploads/icons/books.png">
                        Книги
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>events">
                        <img src="<?php echo base_url()?>uploads/icons/calendar.png">
                        События
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>songs">
                        <img src="<?php echo base_url()?>uploads/icons/quaver.png">
                        Песни
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>albums">
                        <img src="<?php echo base_url()?>uploads/icons/pictures.png">
                        Альбомы
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>folders">
                        <img src="<?php echo base_url()?>uploads/icons/folder.png">
                        Папки
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>gifts">
                        <img src="<?php echo base_url()?>uploads/icons/rewards.png">
                        Подарки
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>stakes">
                        <img src="<?php echo base_url()?>uploads/icons/medal.png">
                        Награды
                    </a>
                </li>
            </ul>
        </div>
        <div class="pos_books col-xs-6 col-sm-9">
            <div class="genres_planshet big-hidden huge-hidden">
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
                <input type="checkbox">Horror
            </div>

        </div>
        <div class="pos_actions small-hidden middle-hidden">
            <div class="pos_genres">
                <div class="genres">
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors<br>
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors<br>
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors<br>
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors<br>
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors<br>
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                    <input type="checkbox" name="categoty_ids[]" value="1"> Horrors
                    <input type="checkbox" name="categoty_ids[]" value="2"> Drama
                </div>
            </div>
            <div class="pos_notif">
                <div class="last_notific">
                    <div class="one_notific">
                        <div class="notif_avatar">
                            <img src="<?php echo base_url()?>uploads/icons/in-love.png">
                        </div>
                        <div class="whos_notif">Monkey D.L. added smth</div>
                    </div>
                    <div class="one_notific">
                        <div class="notif_avatar">
                            <img src="<?php echo base_url()?>uploads/icons/opm.jpg">
                        </div>
                        <div class="whos_notif">Someone added smth</div>
                    </div>
                    <div class="one_notific">
                        <div class="notif_avatar">
                            <img src="<?php echo base_url()?>uploads/icons/meliodas.jpg">
                        </div>
                        <div class="whos_notif">Meliodas added smth</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).scroll(function(){
        if($(document).scrollTop() > $('header').height () + 10){
            $('.menu').eq(0).addClass('fixed');
        }
        else{
            $('.menu').eq(0).removeClass('fixed');
        }
    });
</script>
</body>
</html>