<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'users';

$route['admin_panel'] = 'admins/admin_panel';

$route['documents/(:num)'] = 'documents/index/$1';
$route['one_document/(:num)/(:num)'] = 'documents/one_document/$1/$2';

$route['search_users'] = 'users/search_users';
$route['update'] = 'users/update';

$route['one_book/(:num)'] = 'books/one_book/$1';
$route['one_event/(:any)'] = 'events/one_event/$1';
$route['one_song/(:any)'] = 'songs/one_song/$1';
$route['one_user/(:any)'] = 'users/one_user/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;