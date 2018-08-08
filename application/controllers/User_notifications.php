<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $id = $this->input->post('id');
        $session_user_id = $_SESSION['user_id'];
        $html = '';
        if ($id == $session_user_id) {
            $user_notifications = $this->users_model->getUserNotificationsByUserId($id);

            if (count($user_notifications) == 0) {
                $html .= "<h4 class='centered'>Новых уведомлений нет</h4>";
            } else {
                $html .= "<button class='btn btn-danger btn-delete-notifications' onclick='deleteUserNotificationsByUserId(this)' data-user_id='$session_user_id'>Удалить все мои уведомления</button>";
                foreach ($user_notifications as $user_notification) {
                    $link_id = $user_notification->link_id;
                    $link_table = $user_notification->link_table;
                    $html .= "<div class='centered notification one_notification_$user_notification->id'>
                    <div class='notification-type'>$user_notification->notification_type
                        <span onclick='deleteUserNotification(this)' data-id='$user_notification->id' data-user_id='$user_notification->user_id' class='right'>X</span>
                    </div>
                    <div class='notification-text'>$user_notification->notification_text</div>
                    <div class='notification-date'>$user_notification->notification_date, в $user_notification->notification_time</div>";
                    if ($link_table == 'publications') {
                        $html .= "<a class='notification-link' href='" . base_url() . "one_publication/$link_id'>Смотреть публикацию</a>";
                    } else if ($link_table == 'books') {
                        $html .= "<a class='notification-link' href='" . base_url() . "one_book/$link_id'>Смотреть книгу</a>";
                    } else if ($link_table == 'events') {
                        $html .= "<a class='notification-link' href='" . base_url() . "one_event/$link_id'>Смотреть событие</a>";
                    } else if ($link_table == 'songs') {
                        $html .= "<a class='notification-link' href='" . base_url() . "one_song/$link_id'>Смотреть песню</a>";
                    } else if ($link_table == 'users') {
                        $html .= "<a class='notification-link' href='" . base_url() . "my_page'>Перейти к моей странице.</a>";
                    } else if ($link_table == 'friends') {
                        $html .= "<a class='notification-link' href='" . base_url() . "friends'>Смотреть</a>";
                    } else if ($link_table == 'gifts') {
                        $html .= "<a class='notification-link' href='" . base_url() . "gifts'>Смотреть</a>";
                    }
                    $html .= "</div>";
                }
            }

            $data = array(
                'user_notifications' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            echo json_encode($data);
        }
    }

    public function delete_user_notification() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        if ($user_id == $session_user_id) {
            $this->users_model->deleteUserNotificationById($id);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'notification_error' => 'Не удалось удалить уведомление.',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_user_notifications_by_user_id() {
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        if ($user_id == $session_user_id) {
            $this->users_model->deleteUserNotificationsByUserId($user_id);
            $delete_json = array(
                'notification_success' => 'Ваши уведомления успешно удалены',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'notification_error' => 'Не удалось удалить уведомление.',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}