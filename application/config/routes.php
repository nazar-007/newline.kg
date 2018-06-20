<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'users';
$route['login'] = 'users/login';
$route['logout'] = 'users/logout';
$route['insert_user'] = 'users/insert_user';
$route['insert_friend'] = 'users/insert_friend';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;