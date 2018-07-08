<?php
    if ($song_num_rows != 1) {
        die();
    } else {
        echo "<pre>";
        print_r($one_song);
        echo "</pre>";
    }
?>