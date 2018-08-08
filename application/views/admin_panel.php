<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/books.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/events.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/songs.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/admins.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
</head>
<body>
<div class="container">
    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash; ?>">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="<?php echo base_url()?>admins/logout_admin">Выйти</a>
            <ul>

                <?php
                    echo "<li>
                        <button class='btn btn-info'>
                            <a href='" . base_url() . "admin_panel'>Все материалы</a>
                        </button>
                    </li>
                    <li>
                        <button class='btn btn-info'>
                            <a href='javascript:void(0)' onclick='loadComplaints(this)' data-material='$material'>Жалобы</a>
                        </button>
                    </li>";
                    if ($material == 'book' || $material == 'event' || $material == 'song') {
                        echo "<li>
                            <button class='btn btn-info'>
                              <a href='javascript:void(0)' onclick='loadSuggestions(this)' data-material='$material'>Предложения</a>
                            </button>
                        </li>";
                    }
                ?>
            </ul>
        </div>

        <div id="materials" class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
            <table border="3">
                <?php

                if ($material == 'book') {
                    echo "<h3 class='centered'>Вы - админ книг</h3>";
                    foreach ($books as $book) {
                        $book_id = $book->id;
                        $book_name = $book->book_name;
                        $book_author = $book->book_author;
                        $book_description = $book->book_description;
                        echo "<tr class='one-book-$book_id'>
                            <td>$book_id</td>
                            <td class='one_book_name_$book_id'>$book_name</td>
                            <td>
                                <button onclick='getOneBookByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneBook' data-id='$book_id'><span class='glyphicon glyphicon-align-justify'></span></button>                            
                            </td>
                            <td>
                                <button onclick='deletePressBook(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteBook' data-id='$book_id' data-name='$book_name'><span class='glyphicon glyphicon-trash'></span></button>
                            </td>
                            <td>
                                <button onclick='updatePressBook(this)' type='button' class='btn btn-warning' data-toggle='modal' data-target='#updateBook' data-id='$book_id' data-name='$book_name' data-author='$book_author' data-description='$book_description'><span class='glyphicon glyphicon-edit'></span></button>
                            </td>
                        </tr>";
                    }
                } else if ($material == 'event') {
                    echo "<h3 class='centered'>Вы - админ событий</h3>";
                    foreach ($events as $event) {
                        $event_id = $event->id;
                        $event_name = $event->event_name;
                        $event_description = $event->event_description;
                        $event_address = $event->event_address;
                        echo "<tr class='one-event-$event_id'>
                            <td>$event_id</td>
                            <td class='one_event_name_$event_id'>$event_name</td>
                            <td>
                                <button onclick='getOneEventByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneEvent' data-id='$event_id'><span class='glyphicon glyphicon-align-justify'></span></button>                            
                            </td>
                            <td>
                                <button onclick='deletePressEvent(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteEvent' data-id='$event_id' data-name='$event_name'><span class='glyphicon glyphicon-trash'></span></button>
                            </td>
                            <td>
                                <button onclick='updatePressEvent(this)' type='button' class='btn btn-warning' data-toggle='modal' data-target='#updateEvent' data-id='$event_id' data-name='$event_name' data-description='$event_description' data-address='$event_address'><span class='glyphicon glyphicon-edit'></span></button>
                            </td>
                        </tr>";
                    }
                } else if ($material == 'song') {
                    echo "<h3 class='centered'>Вы - админ песен</h3>";
                    foreach ($songs as $song) {
                        $song_id = $song->id;
                        $song_name = $song->song_name;
                        $song_singer = $song->song_singer;
                        $song_lyrics = $song->song_lyrics;
                        echo "<tr class='one-song-$song_id'>
                            <td>$song_id</td>
                            <td class='one_song_name_$song_id'>$song_singer - $song_name</td>
                            <td>
                                <button onclick='getOneSongByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneSong' data-id='$song_id'><span class='glyphicon glyphicon-align-justify'></span></button>                            
                            </td>
                            <td>
                                <button onclick='deletePressSong(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteSong' data-id='$song_id' data-name='$song_name'><span class='glyphicon glyphicon-trash'></span></button>
                            </td>
                            <td>
                                <button onclick='updatePressSong(this)' type='button' class='btn btn-warning' data-toggle='modal' data-target='#updateSong' data-id='$song_id' data-name='$song_name' data-singer='$song_singer' data-lyrics='$song_lyrics'><span class='glyphicon glyphicon-edit'></span></button>
                            </td>
                        </tr>";
                    }
                } else if ($material == 'publication') {
                    echo "<h3 class='centered'>Вы - админ публикаций</h3>";
                    foreach ($publications as $publication) {
                        $publication_id = $publication->id;
                        $publication_name = $publication->publication_name;
                        $publication_description = $publication->publication_description;
                        echo "<tr class='one-publication-$publication_id'>
                            <td>$publication_id</td>
                            <td class='one_publication_name_$publication_id'>$publication_name</td>
                            <td>
                                <button onclick='getOnePublicationByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOnePublication' data-id='$publication_id'><span class='glyphicon glyphicon-align-justify'></span></button>                            
                            </td>
                            <td>
                                <button onclick='deletePressPublication(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deletePublication' data-id='$publication_id' data-name='$publication_name'><span class='glyphicon glyphicon-trash'></span></button>
                            </td>
                        </tr>";
                    }
                } else if ($material == 'user') {
                    echo "<h3 class='centered'>Вы - админ пользователей</h3>";
                    foreach ($users as $user) {
                        $user_id = $user->id;
                        $user_email = $user->email;
                        echo "<tr class='one-user-$user_id'>
                            <td>$user_id</td>
                            <td class='one_user_name_$user_id'>$user_email</td>
                            <td>
                                <button onclick='getOneUserByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneUser' data-id='$user_id'><span class='glyphicon glyphicon-align-justify'></span></button>                            
                            </td>
                            <td>
                                <button onclick='deletePressUser(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteUser' data-id='$user_id' data-name='$user_email'><span class='glyphicon glyphicon-trash'></span></button>
                            </td>
                        </tr>";
                    }
                }
            ?>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="deleteBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="book_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление жалобы без удаления книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookComplaint(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookComplaintAndBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление книги по жалобе пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookComplaintAndBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_book_id" name="book_id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <input class="delete_name" name="book_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление предложения без добавления книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookSuggestion(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_file" name="book_file" type="hidden">
                        <input class="delete_image" name="book_image" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookSuggestionAndInsertBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Принятие новой книги по предложению пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookSuggestionAndInsertBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <h3 class="insert_question"></h3>
                        <input class="insert_book_name" name="book_name" type="hidden">
                        <input class="insert_book_file" name="book_file" type="hidden">
                        <input class="insert_book_author" name="book_author" type="hidden">
                        <input class="insert_book_description" name="book_description" type="hidden">
                        <input class="insert_book_image" name="book_image" type="hidden">
                        <input class="insert_category_id" name="category_id" type="hidden">
                        <input class="suggested_user_id" name="suggested_user_id" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, добавить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр книги</h4>
            </div>
            <div id="get_one_book" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneBookSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр предложенной книги</h4>
            </div>
            <div id="get_one_book_suggestion" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Редактирование книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="updateBook(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="update_id" name="id" type="hidden">
                        <label>Название книги</label>
                        <input required class="update_name form-control" name="book_name" type="text">
                        <label>Автор книги</label>
                        <input required class="update_author form-control" name="book_author" type="text">
                        <label>Описание книги</label>
                        <textarea required class="update_description form-control" name="book_description" rows="10"></textarea>
                        <br>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, редактировакть</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEvent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление события</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteEvent(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="event_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEventComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление жалобы без удаления события</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteEventComplaint(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEventComplaintAndEvent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление события по жалобе пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteEventComplaintAndEvent(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_event_id" name="event_id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <input class="delete_name" name="event_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEventSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление предложения без добавления события</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteEventSuggestion(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEventSuggestionAndInsertEvent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Принятие нового события по предложению пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteEventSuggestionAndInsertEvent(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <h3 class="insert_question"></h3>
                        <input class="insert_event_name" name="event_name" type="hidden">
                        <input class="insert_event_description" name="event_description" type="hidden">
                        <input class="insert_event_address" name="event_address" type="hidden">
                        <input class="insert_event_start_date" name="event_start_date" type="hidden">
                        <input class="insert_event_start_time" name="event_start_time" type="hidden">
                        <input class="insert_category_id" name="category_id" type="hidden">
                        <input class="suggested_user_id" name="suggested_user_id" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, добавить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneEvent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр события</h4>
            </div>
            <div id="get_one_event" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneEventSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр предложенного события</h4>
            </div>
            <div id="get_one_event_suggestion" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateEvent" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Редактирование события</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="updateEvent(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="update_id" name="id" type="hidden">
                        <label>Название события</label>
                        <input required class="update_name form-control" name="event_name" type="text">
                        <label>Описание события</label>
                        <textarea required class="update_description form-control" name="event_description" rows="10"></textarea>
                        <label>Адрес события</label>
                        <input required class="update_address form-control" name="event_address" type="text">
                        <br>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, редактировакть</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSong" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление песни</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteSong(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="song_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSongComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление жалобы без удаления песни</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteSongComplaint(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSongComplaintAndSong" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление песни по жалобе пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteSongComplaintAndSong(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_song_id" name="song_id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <input class="delete_name" name="song_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSongSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление предложения без добавления песни</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteSongSuggestion(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="text">
                        <input class="delete_file" name="song_file" type="text">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSongSuggestionAndInsertSong" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Принятие новой песни по предложению пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteSongSuggestionAndInsertSong(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <h3 class="insert_question"></h3>
                        <input class="insert_song_name" name="song_name" type="hidden">
                        <input class="insert_song_file" name="song_file" type="hidden">
                        <input class="insert_song_singer" name="song_singer" type="hidden">
                        <input class="insert_song_lyrics" name="song_lyrics" type="hidden">
                        <input class="insert_category_id" name="category_id" type="hidden">
                        <input class="suggested_user_id" name="suggested_user_id" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, добавить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneSong" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр песни</h4>
            </div>
            <div id="get_one_song" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneSongSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр предложенной песни</h4>
            </div>
            <div id="get_one_song_suggestion" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateSong" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Редактирование песни</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="updateSong(this)">
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="update_id" name="id" type="hidden">
                        <label>Название песни</label>
                        <input required class="update_name form-control" name="song_name" type="text">
                        <label>Певец/группа</label>
                        <input required class="update_singer form-control" name="song_singer" type="text">
                        <label>Текст песни</label>
                        <textarea required class="update_lyrics form-control" name="song_lyrics" rows="10"></textarea>
                        <br>
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, редактировакть</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="deleteBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_name" name="book_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookComplaint" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление жалобы без удаления книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookComplaint(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookComplaintAndBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление книги по жалобе пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookComplaintAndBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_book_id" name="book_id" type="hidden">
                        <input class="delete_complaint_text" name="complaint_text" type="hidden">
                        <input class="delete_name" name="book_name" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookSuggestion" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удаление предложения без добавления книги</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookSuggestion(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_file" name="book_file" type="hidden">
                        <input class="delete_image" name="book_image" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, удалить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookSuggestionAndInsertBook" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Принятие новой книги по предложению пользователя</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteBookSuggestionAndInsertBook(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                        <input class="delete_id" name="id" type="hidden">
                        <h3 class="insert_question"></h3>
                        <input class="insert_book_name" name="book_name" type="hidden">
                        <input class="insert_book_file" name="book_file" type="hidden">
                        <input class="insert_book_author" name="book_author" type="hidden">
                        <input class="insert_book_description" name="book_description" type="hidden">
                        <input class="insert_book_image" name="book_image" type="hidden">
                        <input class="insert_category_id" name="category_id" type="hidden">
                        <input class="suggested_user_id" name="suggested_user_id" type="hidden">
                        <span class="action_buttons">
                            <button class="btn btn-primary" type="submit">ОК, добавить</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOnePublication" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр публикации</h4>
            </div>
            <div id="get_one_publication" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getOneUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Просмотр публикации</h4>
            </div>
            <div id="get_one_user" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>

    function deleteBookCommentByAdmin(context) {
        var book_comment_id = context.getAttribute('data-book_comment_id');
        var comment_text = context.getAttribute('data-comment_text');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>book_comments/delete_book_comment_by_admin",
            data: {id: book_comment_id, comment_text: comment_text, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + message.id).fadeOut(500);
            }
        })
    }
    function getOneBookByAdmin(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>books/get_one_book_by_admin",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#get_one_book").html(message.get_one_book);
        })
    }
    function getOneBookSuggestionByAdmin(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var book_name = suggestion_parse[i].book_name;
            var book_file = suggestion_parse[i].book_file;
            var book_author = suggestion_parse[i].book_author;
            var book_description = suggestion_parse[i].book_description;
            var book_image = suggestion_parse[i].book_image;
            var category_id = suggestion_parse[i].category_id;
            $("#get_one_book_suggestion").html("<h3 class='centered'>" + book_name + "</h3>" +
                "<div class='row'>" +
                    "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'> " +
                       "<div>" +
                        "<strong class='book_th'>Автор: </strong> " +
                         "<span class='book_td'>" + book_author + "</span>" +
                    "</div>" +
                    "<div>" +
                        "<strong class='book_th'>Описание: </strong>" +
                        "<span class='book_td'>" + book_description + "</span> " +
                        "</div> " +
                    "<div> " +
                        "<strong class='book_th'>ID категории: </strong> " +
                        "<span class='book_td'>" + category_id + "</span>" +
                    "</div>" +
                    "</div>" +
                    "<div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>" +
                    "<div class='book_cover'>" +
                        "<img style='width: 168px;' class='book_image absolute_book' src='<?php echo base_url()?>uploads/images/book_images/" + book_image + "'> " +
                    "</div>" +
                "</div>" +
                "</div>" +
                "<div class='book-iframe'>" +
                    "<iframe width='560' height='315' src='<?php echo base_url()?>uploads/book_files/" + book_file + "' frameborder='0'></iframe> " +
                "</div>"
            );
        }
    }
    function deletePressBook(context) {
        var id = context.getAttribute('data-id');
        var book_name = context.getAttribute('data-name');
        $(".delete_id").val(id);
        $(".delete_name").val(book_name);
        $(".delete_question").html("Вы действительно хотите удалить книгу " + book_name + ' со всеми данными?');
    }
    function deleteBook(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "books/delete_book",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteBook").trigger('click');
            if (message.book_error) {
                alert(message.book_error);
            } else {
                $(".one-book-" + message.id).remove();
            }
        });
    }
    function deletePressBookComplaint(context) {
        var id = context.getAttribute('data-complaint_id');
        var book_name = context.getAttribute('data-book_name');
        var complaint_text = context.getAttribute('data-complaint_text');
        $(".delete_id").val(id);
        $(".delete_name").val(book_name);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_question").html("Вы действительно хотите удалить жалобу '" + complaint_text + "' и оставить книгу в покое?");
    }
    function deletePressBookComplaintAndDeletePressBook(context) {
        var id = context.getAttribute('data-complaint_id');
        var book_id = context.getAttribute('data-book_id');
        var complaint_text = context.getAttribute('data-complaint_text');
        var book_name = context.getAttribute('data-book_name');
        $(".delete_id").val(id);
        $(".delete_book_id").val(book_id);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_name").val(book_name);
        $(".delete_question").html("Вы действительно хотите удалить книгу '" + book_name + "' из-за жалобы '" + complaint_text + "' со всеми данными?");
    }
    function deleteBookComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "book_complaints/delete_book_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteBookComplaint").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                $(".one-complaint-" + message.id).remove();
            }
        });
    }
    function deleteBookComplaintAndBook(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "book_complaints/delete_book_complaint_and_book",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteBookComplaintAndBook").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                alert(message.complaint_success);
                location.reload(true);
            }
        });
    }
    function deletePressBookSuggestion(context) {
        var id = context.getAttribute('data-id');
        var book_file = context.getAttribute('data-file');
        var book_image = context.getAttribute('data-image');
        $(".delete_id").val(id);
        $(".delete_file").val(book_file);
        $(".delete_image").val(book_image);
        $(".delete_question").html("Вы действительно хотите удалить предложение на добавление книги?");
    }
    function deleteBookSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "book_suggestions/delete_book_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteBookSuggestion").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                $(".one-suggestion-" + message.id).remove();
            }
        });
    }
    function deletePressBookSuggestionAndInsertPressBook(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var book_name = suggestion_parse[i].book_name;
            var book_file = suggestion_parse[i].book_file;
            var book_author = suggestion_parse[i].book_author;
            var book_description = suggestion_parse[i].book_description;
            var book_image = suggestion_parse[i].book_image;
            var category_id = suggestion_parse[i].category_id;
            $(".insert_book_name").val(book_name);
            $(".insert_book_file").val(book_file);
            $(".insert_book_author").val(book_author);
            $(".insert_book_description").val(book_description);
            $(".insert_book_image").val(book_image);
            $(".insert_category_id").val(category_id);
        }
        var id = context.getAttribute('data-id');
        var suggested_user_id = context.getAttribute('data-suggested_user_id');
        $(".delete_id").val(id);
        $(".suggested_user_id").val(suggested_user_id);
        $(".insert_question").html("Вы действительно хотите добавить книгу " + book_name + " в общий список всех книг?");
    }
    function deleteBookSuggestionAndInsertBook(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "book_suggestions/delete_book_suggestion_and_insert_book",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteBookSuggestionAndInsertBook").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                alert(message.suggestion_success);
                location.reload(true);
            }
        });
    }
    function updatePressBook(context) {
        var id = context.getAttribute('data-id');
        var book_name = context.getAttribute('data-name');
        var book_author = context.getAttribute('data-author');
        var book_description = context.getAttribute('data-description');
        $(".update_id").val(id);
        $(".update_name").val(book_name);
        $(".update_author").val(book_author);
        $(".update_description").val(book_description);
    }
    function updateBook(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "books/update_book",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $(".form-control").val('');
            if (message.update_error) {
                alert(message.update_error);
            } else {
                $("#updateBook").trigger('click');
                $(".one_book_name_" + message.id).html(message.book_name);
            }

        })
    }

    function deleteEventCommentByAdmin(context) {
        var event_comment_id = context.getAttribute('data-event_comment_id');
        var comment_text = context.getAttribute('data-comment_text');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>event_comments/delete_event_comment_by_admin",
            data: {id: event_comment_id, comment_text: comment_text, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + message.id).fadeOut(500);
            }
        })
    }
    function getOneEventByAdmin(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>events/get_one_event_by_admin",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#get_one_event").html(message.get_one_event);
        })
    }
    function getOneEventSuggestionByAdmin(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var event_name = suggestion_parse[i].event_name;
            var event_description = suggestion_parse[i].event_description;
            var event_address = suggestion_parse[i].event_address;
            var event_start_date = suggestion_parse[i].event_start_date;
            var event_start_time = suggestion_parse[i].event_start_time;
            var category_id = suggestion_parse[i].category_id;
            $("#get_one_event_suggestion").html("<h3 class='centered'>" + event_name + "</h3>" +
                "<div>" +
                "<div> " +
                "<strong class='event_th'>Описание: </strong>" +
                "<span class='event_td'>" + event_description + "</span> " +
                "</div> " +
                "<div> " +
                "<strong class='event_th'>Место события: </strong> " +
                "<span class='event_td'>" + event_address + "</span> " +
                "</div> " +
                "<div> " +
                "<strong class='event_th'>Дата: </strong>" +
                "<span class='event_td'>" + event_start_date + "</span> " +
                "</div> " +
                "<div> " +
                "<strong class='event_th'>Время: </strong>" +
                "<span class='event_td'>" + event_start_time + "</span> " +
                "</div> " +
                "<div> " +
                "<strong class='event_th'>Категория: </strong> " +
                "<span class='event_td'>" + category_id + "</span> " +
                "</div> " +
                "</div>"
            );
        }
    }
    function deletePressEvent(context) {
        var id = context.getAttribute('data-id');
        var event_name = context.getAttribute('data-name');
        $(".delete_id").val(id);
        $(".delete_name").val(event_name);
        $(".delete_question").html("Вы действительно хотите удалить событие " + event_name + ' со всеми данными?');
    }
    function deleteEvent(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "events/delete_event",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteEvent").trigger('click');
            if (message.event_error) {
                alert(message.event_error);
            } else {
                $(".one-event-" + message.id).remove();
            }
        });
    }
    function deletePressEventComplaint(context) {
        var id = context.getAttribute('data-complaint_id');
        var event_name = context.getAttribute('data-event_name');
        var complaint_text = context.getAttribute('data-complaint_text');
        $(".delete_id").val(id);
        $(".delete_name").val(event_name);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_question").html("Вы действительно хотите удалить жалобу '" + complaint_text + "' и оставить событие в покое?");
    }
    function deletePressEventComplaintAndDeletePressEvent(context) {
        var id = context.getAttribute('data-complaint_id');
        var event_id = context.getAttribute('data-event_id');
        var complaint_text = context.getAttribute('data-complaint_text');
        var event_name = context.getAttribute('data-event_name');
        $(".delete_id").val(id);
        $(".delete_event_id").val(event_id);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_name").val(event_name);
        $(".delete_question").html("Вы действительно хотите удалить событие '" + event_name + "' из-за жалобы '" + complaint_text + "' со всеми данными?");
    }
    function deleteEventComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "event_complaints/delete_event_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteEventComplaint").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                $(".one-complaint-" + message.id).remove();
            }
        });
    }
    function deleteEventComplaintAndEvent(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "event_complaints/delete_event_complaint_and_event",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteEventComplaintAndEvent").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                alert(message.complaint_success);
                location.reload(true);
            }
        });
    }
    function deletePressEventSuggestion(context) {
        var id = context.getAttribute('data-id');
        $(".delete_id").val(id);
        $(".delete_question").html("Вы действительно хотите удалить предложение на добавление события?");
    }
    function deleteEventSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "event_suggestions/delete_event_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteEventSuggestion").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                $(".one-suggestion-" + message.id).remove();
            }
        });
    }
    function deletePressEventSuggestionAndInsertPressEvent(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var event_name = suggestion_parse[i].event_name;
            var event_description = suggestion_parse[i].event_description;
            var event_address = suggestion_parse[i].event_address;
            var event_start_date = suggestion_parse[i].event_start_date;
            var event_start_time = suggestion_parse[i].event_start_time;
            var category_id = suggestion_parse[i].category_id;
            $(".insert_event_name").val(event_name);
            $(".insert_event_description").val(event_description);
            $(".insert_event_address").val(event_address);
            $(".insert_event_start_date").val(event_start_date);
            $(".insert_event_start_time").val(event_start_time);
            $(".insert_category_id").val(category_id);
        }
        var id = context.getAttribute('data-id');
        var suggested_user_id = context.getAttribute('data-suggested_user_id');
        $(".delete_id").val(id);
        $(".suggested_user_id").val(suggested_user_id);
        $(".insert_question").html("Вы действительно хотите добавить событие " + event_name + " в общий список всех событий?");
    }
    function deleteEventSuggestionAndInsertEvent(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "event_suggestions/delete_event_suggestion_and_insert_event",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteEventSuggestionAndInsertEvent").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                alert(message.suggestion_success);
                location.reload(true);
            }
        });
    }
    function updatePressEvent(context) {
        var id = context.getAttribute('data-id');
        var event_name = context.getAttribute('data-name');
        var event_description = context.getAttribute('data-description');
        var event_address = context.getAttribute('data-address');
        $(".update_id").val(id);
        $(".update_name").val(event_name);
        $(".update_description").val(event_description);
        $(".update_address").val(event_address);
    }
    function updateEvent(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "events/update_event",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $(".form-control").val('');
            if (message.update_error) {
                alert(message.update_error);
            } else {
                $("#updateEvent").trigger('click');
                $(".one_event_name_" + message.id).html(message.event_name);
            }

        })
    }

    function deleteSongCommentByAdmin(context) {
        var song_comment_id = context.getAttribute('data-song_comment_id');
        var comment_text = context.getAttribute('data-comment_text');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>song_comments/delete_song_comment_by_admin",
            data: {id: song_comment_id, comment_text: comment_text, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            if (message.comment_error) {
                alert(message.comment_error);
            } else {
                $('.one_comment_' + message.id).fadeOut(500);
            }
        })
    }
    function getOneSongByAdmin(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>songs/get_one_song_by_admin",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#get_one_song").html(message.get_one_song);
        })
    }
    function getOneSongSuggestionByAdmin(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var song_name = suggestion_parse[i].song_name;
            var song_file = suggestion_parse[i].song_file;
            var song_singer = suggestion_parse[i].song_singer;
            var song_lyrics = suggestion_parse[i].song_lyrics;
            var category_id = suggestion_parse[i].category_id;
            $("#get_one_song_suggestion").html("<h3 class='centered'>" + song_singer + " - " + song_name + "</h3> " +
                "<div>" +
                    "<audio class='one-player' autoplay src='<?php echo base_url()?>uploads/song_files/" + song_file + "' controls controlsList='nodownload'></audio> " +
                "</div> " +
                "<div> " +
                "<strong class='song_th'>Текст песни: </strong> " +
                "<span class='song_td'> " +
                "<pre>" + song_lyrics + "</pre> " +
                "</span> " +
                "</div> " +
                "<div> " +
                "<strong class='song_th'>Категория: </strong> " +
                "<span class='song_td'>" + category_id + "</span> " +
                "</div>"
            );
        }
    }
    function deletePressSong(context) {
        var id = context.getAttribute('data-id');
        var song_name = context.getAttribute('data-name');
        $(".delete_id").val(id);
        $(".delete_name").val(song_name);
        $(".delete_question").html("Вы действительно хотите удалить песню " + song_name + ' со всеми данными?');
    }
    function deleteSong(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "songs/delete_song",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteSong").trigger('click');
            if (message.song_error) {
                alert(message.song_error);
            } else {
                $(".one-song-" + message.id).remove();
            }
        });
    }
    function deletePressSongComplaint(context) {
        var id = context.getAttribute('data-complaint_id');
        var song_name = context.getAttribute('data-song_name');
        var complaint_text = context.getAttribute('data-complaint_text');
        $(".delete_id").val(id);
        $(".delete_name").val(song_name);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_question").html("Вы действительно хотите удалить жалобу '" + complaint_text + "' и оставить песню в покое?");
    }
    function deletePressSongComplaintAndDeletePressSong(context) {
        var id = context.getAttribute('data-complaint_id');
        var song_id = context.getAttribute('data-song_id');
        var complaint_text = context.getAttribute('data-complaint_text');
        var song_name = context.getAttribute('data-song_name');
        $(".delete_id").val(id);
        $(".delete_song_id").val(song_id);
        $(".delete_complaint_text").val(complaint_text);
        $(".delete_name").val(song_name);
        $(".delete_question").html("Вы действительно хотите удалить песню '" + song_name + "' из-за жалобы '" + complaint_text + "' со всеми данными?");
    }
    function deleteSongComplaint(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "song_complaints/delete_song_complaint",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteSongComplaint").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                $(".one-complaint-" + message.id).remove();
            }
        });
    }
    function deleteSongComplaintAndSong(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "song_complaints/delete_song_complaint_and_song",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteSongComplaintAndSong").trigger('click');
            if (message.complaint_error) {
                alert(message.complaint_error);
            } else {
                alert(message.complaint_success);
                location.reload(true);
            }
        });
    }
    function deletePressSongSuggestion(context) {
        var id = context.getAttribute('data-id');
        var song_file = context.getAttribute('data-file');
        $(".delete_id").val(id);
        $(".delete_file").val(song_file);
        $(".delete_question").html("Вы действительно хотите удалить предложение на добавление песни?");
    }
    function deleteSongSuggestion(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "song_suggestions/delete_song_suggestion",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteSongSuggestion").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                $(".one-suggestion-" + message.id).remove();
            }
        });
    }
    function deletePressSongSuggestionAndInsertPressSong(context) {
        var suggestion_stringify = context.getAttribute('data-suggestion-json');
        var suggestion_parse = JSON.parse(suggestion_stringify);
        for(var i = 0; i < suggestion_parse.length; i++) {
            var song_name = suggestion_parse[i].song_name;
            var song_file = suggestion_parse[i].song_file;
            var song_singer = suggestion_parse[i].song_singer;
            var song_lyrics = suggestion_parse[i].song_lyrics;
            var category_id = suggestion_parse[i].category_id;
            $(".insert_song_name").val(song_name);
            $(".insert_song_file").val(song_file);
            $(".insert_song_singer").val(song_singer);
            $(".insert_song_lyrics").val(song_lyrics);
            $(".insert_category_id").val(category_id);
        }
        var id = context.getAttribute('data-id');
        var suggested_user_id = context.getAttribute('data-suggested_user_id');
        $(".delete_id").val(id);
        $(".suggested_user_id").val(suggested_user_id);
        $(".insert_question").html("Вы действительно хотите добавить песню " + song_name + " в общий список всех песен?");
    }
    function deleteSongSuggestionAndInsertSong(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "song_suggestions/delete_song_suggestion_and_insert_song",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $("#deleteSongSuggestionAndInsertSong").trigger('click');
            if (message.suggestion_error) {
                alert(message.suggestion_error);
            } else {
                alert(message.suggestion_success);
                location.reload(true);
            }
        });
    }
    function updatePressSong(context) {
        var id = context.getAttribute('data-id');
        var song_name = context.getAttribute('data-name');
        var song_singer = context.getAttribute('data-singer');
        var song_lyrics = context.getAttribute('data-lyrics');
        $(".update_id").val(id);
        $(".update_name").val(song_name);
        $(".update_singer").val(song_singer);
        $(".update_lyrics").val(song_lyrics);
    }
    function updateSong(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "songs/update_song",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            $(".form-control").val('');
            if (message.update_error) {
                alert(message.update_error);
            } else {
                $("#updateSong").trigger('click');
                $(".one_song_name_" + message.id).html(message.song_singer + ' - ' + message.song_name);
            }

        })
    }

    function getOnePublicationByAdmin(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>publications/get_one_publication_by_admin",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#get_one_publication").html(message.get_one_publication);
        })
    }

    function getOneUserByAdmin(context) {
        var id = context.getAttribute('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/get_one_user_by_admin",
            data: {id: id, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function (message) {
            $(".csrf").val(message.csrf_hash);
            $("#get_one_user").html(message.get_one_user);
        })
    }

    function loadComplaints(context) {
        var material = context.getAttribute('data-material');
        if (material == 'book' || material == 'event' || material == 'song' || material == 'publication' || material == 'user') {
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>" + material + "_complaints",
                data: {material: material, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $(".csrf").val(message.csrf_hash);
                if (material == 'book') {
                    $("#materials").html(message.book_complaints);
                } else if (material == 'event') {
                    $("#materials").html(message.event_complaints);
                } else if (material == 'song') {
                    $("#materials").html(message.song_complaints);
                } else if (material == 'publication') {
                    $("#materials").html(message.publication_complaints);
                } else if (material == 'user') {
                    $("#materials").html(message.user_complaints);
                }
            })
        } else {
            alert("Не удалось загрузить запрашиваемую страницу!");
        }
    }
    function loadSuggestions(context) {
        var material = context.getAttribute('data-material');
        if (material == 'book' || material == 'event' || material == 'song') {
            $.ajax({
                method: "POST",
                url: "<?php echo base_url()?>" + material + "_suggestions",
                data: {material: material, csrf_test_name: $(".csrf").val()},
                dataType: "JSON"
            }).done(function (message) {
                $(".csrf").val(message.csrf_hash);
                if (material == 'book') {
                    $("#materials").html(message.book_suggestions);
                } else if (material == 'event') {
                    $("#materials").html(message.event_suggestions);
                } else if (material == 'song') {
                    $("#materials").html(message.song_suggestions);
                }

                if (message.suggestion_error) {
                    alert(message.suggestion_error);
                }
            })
        } else {
            alert("Не удалось загрузить запрашиваемую страницу!");
        }
    }

    $("#deleteBook").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteBook").modal('hide');
        };
    });
    $("#deleteBookComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteBookComplaint").modal('hide');
        };
    });
    $("#deleteBookComplaintAndBook").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteBookComplaintAndBook").modal('hide');
        };
    });
    $("#deleteBookSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteBookSuggestion").modal('hide');
        };
    });
    $("#deleteBookSuggestionAndInsertBook").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteBookSuggestionAndInsertBook").modal('hide');
        };
    });
    $("#getOneBook").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneBook").modal('hide');
        };
    });
    $("#getOneBookSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneBookSuggestion").modal('hide');
        };
    });
    $("#updateBook").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#updateBook").modal('hide');
        };
    });
    $("#deleteEvent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteEvent").modal('hide');
        };
    });
    $("#deleteEventComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteEventComplaint").modal('hide');
        };
    });
    $("#deleteEventComplaintAndEvent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteEventComplaintAndEvent").modal('hide');
        };
    });
    $("#deleteEventSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteEventSuggestion").modal('hide');
        };
    });
    $("#deleteEventSuggestionAndInsertEvent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteEventSuggestionAndInsertEvent").modal('hide');
        };
    });
    $("#getOneEvent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneEvent").modal('hide');
        };
    });
    $("#getOneEventSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneEventSuggestion").modal('hide');
        };
    });
    $("#updateEvent").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#updateEvent").modal('hide');
        };
    });
    $("#deleteSong").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteSong").modal('hide');
        };
    });
    $("#deleteSongComplaint").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteSongComplaint").modal('hide');
        };
    });
    $("#deleteSongComplaintAndSong").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteSongComplaintAndSong").modal('hide');
        };
    });
    $("#deleteSongSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteSongSuggestion").modal('hide');
        };
    });
    $("#deleteSongSuggestionAndInsertSong").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#deleteSongSuggestionAndInsertSong").modal('hide');
        };
    });
    $("#getOneSong").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneSong").modal('hide');
        };
    });
    $("#getOneSongSuggestion").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#getOneSongSuggestion").modal('hide');
        };
    });
    $("#updateSong").on('show.bs.modal', function () {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            $("#updateSong").modal('hide');
        };
    });

</script>
</body>
</html>