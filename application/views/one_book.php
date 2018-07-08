<?php
    if ($book_num_rows != 1) {
        die();
    } else {
        echo "<pre>";
        print_r($one_book);
        echo "</pre>";
    }
?>