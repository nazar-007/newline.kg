<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_image_emotions', $data);
    }


    public function insert_user_image_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_image_id = $this->input->post('user_image_id');
        $user_id = $this->input->post('user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_user_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id,
            'user_id' => $user_id,
            'emotion_id' => $emotion_id
        );
        $this->users_model->insertUserImageEmotion($data_user_image_emotions);

        $notification_text = 'Пользователь Назар оставил эмоцию на Вашу фотку';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Вашу фотку',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_user_image_emotion() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserImageEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_user_image_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_image_id = $this->input->post('user_image_id');
        $user_id = $this->input->post('user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_user_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id,
            'user_id' => $user_id,
            'emotion_id' => $emotion_id
        );
        $this->users_model->updateUserImageEmotionById($id, $data_user_image_emotions);
    }
}