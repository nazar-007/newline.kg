<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'users';

$route['admin_panel'] = 'admins/admin_panel';

$route['documents/(:num)'] = 'documents/index/$1';
$route['one_document/(:num)/(:num)'] = 'documents/one_document/$1/$2';

$route['search_users'] = 'users/search_users';
$route['update'] = 'users/update';

$route['restore'] = 'users/restore';

$route['user_images/(:num)'] = 'user_images/index/$1';
$route['publication_images/(:num)'] = 'publication_images/index/$1';

$route['my_page'] = 'users/my_page';

$route['one_book/(:num)'] = 'books/one_book/$1';
$route['one_event/(:num)'] = 'events/one_event/$1';
$route['one_publication/(:num)'] = 'publications/one_publication/$1';
$route['one_song/(:num)'] = 'songs/one_song/$1';
$route['one_user/(:any)'] = 'users/one_user/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;