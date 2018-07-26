<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $user_image_id = $this->input->post('user_image_id');
        $user_image_emotions = $this->songs_model->getUserImageEmotionsByUserImageId($user_image_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($user_image_emotions) == 0 ) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($user_image_emotions as $user_image_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$user_image_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$user_image_emotion->main_image' class='action_avatar'>
                            </div>
                            <div class='emotion_user_name'>
                                $user_image_emotion->nickname $user_image_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_image_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }


    public function insert_user_image_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_image_id = $this->input->post('user_image_id');

        $data_user_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id
        );
        $this->users_model->insertUserImageEmotion($data_user_image_emotions);

        $notification_text = 'Пользователь Назар оставил эмоцию на Вашу фотку';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Вашу фотку',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'link_id' => $user_image_id,
            'link_table' => 'user_images',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);

        $user_image_action = 'Пользователь Назар поставил эмоцию на фотку пользователя Edil';

        $data_user_image_actions = array(
            'user_image_action' => $user_image_action,
            'user_image_time_unix' => time(),
            'user_id' => $user_id,
            'action_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id
        );
        $this->users_model->insertUserImageAction($data_user_image_actions);
    }

    public function delete_user_image_emotion() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserImageEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}