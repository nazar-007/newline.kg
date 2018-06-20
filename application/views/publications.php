
<?php

if ($_SESSION['user_id']) {
    echo 'sessiya est!';
} else {
    echo 'sessii net!';
}

?>
<h1>User_id: <?php echo $_SESSION['user_id'];?></h1>

<a href="/users/logout">Выйти</a>
