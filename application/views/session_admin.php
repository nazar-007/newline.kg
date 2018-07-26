<?php

if (!$_SESSION['admin_id'] && !$_SESSION['admin_email'] && !$_SESSION['admin_table']) {
    redirect(base_url() . 'admins');
}