<?php
    if ($user_num_rows != 1) {
        die();
    } else {
        echo "<pre>";
        print_r($one_user);
        if ($total_common_books > 0) {
            echo 'общие книги: ' . $total_common_books;
        }
        echo "</pre>";
    }
?>