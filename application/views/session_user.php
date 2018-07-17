<?php

if (!$_SESSION['user_id'] && !$_SESSION['user_email']) {
    redirect(base_url());
}