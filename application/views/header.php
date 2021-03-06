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
                    <form method="get" action="<?php echo base_url()?>search_users">
                        <div class="relative">
                            <input required class="form-control" type="text" name="search" size="50" placeholder="Поиск людей">
                            <button class="search-img mobile-search" type="submit"></button>
                        </div>
                    </form>
                </li>
                <a href="<?php echo base_url()?>my_page">
                    <li>
                            Моя страница
                    </li>
                </a>
                <a href="<?php echo base_url()?>publications">
                    <li>
                            Публикации
                    </li>
                </a>
                <a href="<?php echo base_url()?>friends">
                    <li>
                            Друзья
                    </li>
                </a>
                <a href="<?php echo base_url()?>books">
                    <li>
                            Книги
                    </li>
                </a>
                <a href="<?php echo base_url()?>events">
                    <li>
                            События
                    </li>
                </a>
                <a href="<?php echo base_url()?>songs">
                    <li>
                            Песни
                    </li>
                </a>
                <a href="<?php echo base_url()?>guests">
                    <li>
                            Гости
                    </li>
                </a>
                <a href="<?php echo base_url()?>albums">
                    <li>
                            Фотографии
                    </li>
                </a>
                <a href="<?php echo base_url()?>folders">
                    <li>
                            Документы
                    </li>
                </a>
                <a href="<?php echo base_url()?>gifts">
                    <li>
                            Подарки
                    </li>
                </a>
                <a href="<?php echo base_url()?>stakes">
                    <li>
                            Награды
                    </li>
                </a>
                <a href="<?php echo base_url()?>feedback_messages">
                    <li>
                            Письмо разработчикам
                    </li>
                </a>
                <a href="<?php echo base_url()?>users/logout">
                    <li>
                            Выйти
                    </li>
                </a>
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
                    <input required class="search-input" type="text" name="search" size="50" placeholder="Поиск людей">
                    <button class="search-img" type="submit"></button>
                </div>
            </form>
        </div>
        <div class="pos_avatar small-hidden">
            <img onclick="getMyNotifications(this)" data-toggle="modal" data-target="#getMyNotifications" src="<?php echo base_url()?>uploads/icons/notification.png">
        </div>
    </nav>
</header>



