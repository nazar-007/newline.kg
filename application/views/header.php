<header>
    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
    <nav class="menu">
        <div class="pos_logo small-hidden">
            <a href="<?php echo base_url()?>publications">
                <img class="logo" src="<?php echo base_url()?>uploads/icons/logo2.png" alt="newline.kg">
            </a>
        </div>
        <div class="pos_search small-hidden">
            <input type="search" name="search" class="form-control" size="50">
        </div>
        <div class="pos_avatar small-hidden">
            <a href="<?php echo base_url()?>my_page">
                <img class="avatar" src="<?php echo base_url()?>uploads/images/user_images/medal.png">
            </a>
        </div>
    </nav>
</header>