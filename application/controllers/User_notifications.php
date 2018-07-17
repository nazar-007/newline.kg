<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index()
    {
//        echo '<pre>';
//        foreach ($user_notifications as $user_notification) {
//            $link_id = $user_notification->link_id;
//            $link_table = $user_notification->link_table;
//            echo "<h2>".$user_notification->notification_type . "</h2>";
//            echo "<p>".$user_notification->notification_text . "</p>";
//            echo "<b>".$user_notification->notification_date . ' ' . $user_notification->notification_time ."</b>";
//            if ($link_table == 'publications') {
//                echo "<a href='/one_publication/$link_id'>Смотреть публикацию</a>";
//            } else if ($link_table == 'books') {
//                echo "<a href='/one_book/$link_id'>Смотреть книгу</a>";
//            } else if ($link_table == 'events') {
//                echo "<a href='/one_event/$link_id'>Смотреть событие</a>";
//            } else if ($link_table == 'songs') {
//                echo "<a href='/one_song/$link_id'>Смотреть песню</a>";
//            } else if ($link_table == 'user_images') {
//                echo "<a href='/one_user_image/$link_id'>Смотреть фотографию.</a>";
//            } else if ($link_table == 'users') {
//                echo "<a href='/my_page'>Перейти к моей странице.</a>";
//            }
//        }
//        echo '</pre>';
    }

    public function delete_user_notification() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserNotificationByUd($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_user_notifications_by_user_id() {
        $user_id = $this->input->post('user_id');
        $this->users_model->deleteUserNotificationsByUserId($user_id);
        $delete_json = array(
            'user_id' => $user_id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}