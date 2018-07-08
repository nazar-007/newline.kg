<?php
    if ($event_num_rows != 1) {
        die();
    } else {
        echo "<pre>";
        print_r($one_user);
        echo "</pre>";
    }
?>