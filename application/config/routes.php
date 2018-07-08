<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'users';

$route['one_book/(:num)'] = 'users/one_book/$1';
$route['one_event/(:num)'] = 'users/one_event/$1';
$route['one_song/(:num)'] = 'users/one_song/$1';
$route['one_user/(:any)'] = 'users/one_user/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;