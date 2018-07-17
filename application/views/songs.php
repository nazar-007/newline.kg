<!DOCTYPE html>
<html>
<head>
    <title>Книги</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/books.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php

        foreach ($user_notifications as $user_notification) {
            $link_id = $user_notification->link_id;
            $link_table = $user_notification->link_table;
            echo "<h2>".$user_notification->notification_type . "</h2>";
            echo "<p>".$user_notification->notification_text . "</p>";
            echo "<b>".$user_notification->notification_date . ' ' . $user_notification->notification_time ."</b>";
            if ($link_table == 'publications') {
                echo "<a href='/one_publication/$link_id'>Смотреть публикацию</a>";
            } else if ($link_table == 'books') {
                echo "<a href='/one_book/$link_id'>Смотреть книгу</a>";
            } else if ($link_table == 'events') {
                echo "<a href='/one_event/$link_id'>Смотреть событие</a>";
            } else if ($link_table == 'songs') {
                echo "<a href='/one_song/$link_id'>Смотреть песню</a>";
            }
        }

?>

</body>
</html>