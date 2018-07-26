<header>
    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
    <div class="phone_logo big-hidden middle-hidden huge-hidden">
        <div class="mobile-menu big-hidden middle-hidden huge-hidden">
            <img class="logo-mobile" src="<?php echo base_url()?>uploads/icons/newline.png">
            <img id="showMobileMenu" src="<?php echo base_url()?>uploads/icons/menu.png" class="scrolldown show-mobile-catalog">
            <img src="<?php echo base_url()?>uploads/icons/up-arrow.png" class="up-arrow-mobile scrollup">
            <img class="mobile-notification" onclick="getMyNotifications(this)" data-toggle="modal" data-target="#getMyNotifications" src="<?php echo base_url()?>uploads/icons/notification.png">
        </div>
        <div id="mobileMenu" class="small-hidden mobMenuContent" style="display: none">
            <ul class="mobile-catalog">
                <li>
                    <a href="<?php echo base_url()?>my_page">
                        Моя страница
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>publications">
                        Публикации
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>friends">
                        Друзья
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>books">
                        Книги
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>events">
                        События
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>songs">
                        Песни
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>guests">
                        Гости
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>albums">
                        Фотографии
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>folders">
                        Документы
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>gifts">
                        Подарки
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>stakes">
                        Награды
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url()?>users/logout">
                        Выйти
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <nav class="menu">
        <div class="pos_logo small-hidden">
            <a href="<?php echo base_url()?>publications">
                <img class="logo" src="<?php echo base_url()?>uploads/icons/logo.png" alt="newline.kg">
            </a>
        </div>
        <div class="pos_search small-hidden">
            <form method="get" action="<?php echo base_url()?>search_users">
                <div class="search">
                    <input required class="search-input" id="search" type="text" name="search" size="50" placeholder="Поиск">
                    <button class="search-img" type="submit"></button>
                </div>
            </form>
        </div>
        <div class="pos_avatar small-hidden">
            <img onclick="getMyNotifications(this)" data-toggle="modal" data-target="#getMyNotifications" src="<?php echo base_url()?>uploads/icons/notification.png">
            <a href="<?php echo base_url()?>my_page">
                <img class="avatar" src="<?php echo base_url()?>uploads/images/user_images/">
            </a>
        </div>
    </nav>
</header>

<script>

    function searcha() {
        console.log($("#search").val());
    }
</script>





