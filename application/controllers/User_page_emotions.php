<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_page_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');

        $user_id = $this->input->post('user_id');
        $user_page_emotions = $this->users_model->getUserPageEmotionsByUserId($user_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($user_page_emotions) == 0 ) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($user_page_emotions as $user_page_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$user_page_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$user_page_emotion->main_image' class='action_avatar' style='width: 100px;'>
                            </div>
                            <div class='emotion_user_name'>
                                $user_page_emotion->nickname $user_page_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'id' =>$user_id,
            'one_user_page_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }

    public function insert_user_page_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->users_model->getUserPageEmotionNumRowsByUserIdAndEmotionedUserId($user_id, $emotioned_user_id);

        if ($user_id == $emotioned_user_id) {
            $insert_json = array(
                'emotion_error' => "Вы не можете ставить эмоцию на свою страницу!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            if ($emotion_num_rows == 0 && $emotioned_user_id == $session_user_id) {
                $data_user_page_emotions = array(
                    'emotion_date' => $emotion_date,
                    'emotion_time' => $emotion_time,
                    'user_id' => $user_id,
                    'emotioned_user_id' => $emotioned_user_id
                );
                $this->users_model->insertUserPageEmotion($data_user_page_emotions);

                $user_name = $this->users_model->getNicknameAndSurnameById($emotioned_user_id);
                $notification_text = "$user_name поставил эмоцию на Вашу страницу.";

                $total_emotions = $this->users_model->getTotalByUserIdAndUserTable($user_id, 'user_page_emotions');
                $data_user_notifications = array(
                    'notification_type' => 'Эмоция на Вашу страницу',
                    'notification_text' => $notification_text,
                    'notification_date' => $emotion_date,
                    'notification_time' => $emotion_time,
                    'notification_viewed' => 'Не просмотрено',
                    'link_id' => $user_id,
                    'link_table' => 'users',
                    'user_id' => $user_id
                );
                $this->users_model->insertUserNotification($data_user_notifications);
                $insert_json = array(
                    'emotion_num_rows' => $emotion_num_rows,
                    'total_emotions' => $total_emotions,
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            } else {
                $insert_json = array(
                    'emotion_num_rows' => $emotion_num_rows,
                    'emotion_error' => "Вы уже ставили эмоцию на эту страницу или что-то пошло не так!",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            }
        }
        echo json_encode($insert_json);
    }

    public function delete_user_page_emotion() {
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->users_model->getUserPageEmotionNumRowsByUserIdAndEmotionedUserId($user_id, $emotioned_user_id);
        if ($emotion_num_rows > 0 && $emotioned_user_id == $session_user_id) {
            $this->users_model->deleteUserPageEmotionByUserIdAndEmotionedUserId($user_id, $emotioned_user_id);
            $total_emotions = $this->users_model->getTotalByUserIdAndUserTable($user_id, 'user_page_emotions');
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на эту страницу или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}