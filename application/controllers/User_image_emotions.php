<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_image_emotions', $data);
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