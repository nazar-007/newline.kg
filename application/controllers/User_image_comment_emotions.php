<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_comment_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_image_comment_emotions', $data);
    }


    public function insert_user_image_comment_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_image_id = $this->input->post('user_image_id');
        $user_image_comment_id = $this->input->post('user_image_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_user_image_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id,
            'user_image_comment_id' => $user_image_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->users_model->insertUserImageCommentEmotion($data_user_image_comment_emotions);

        $notification_text = 'Пользователь Назар поставил эмоцию на Ваш коммент "Супер!" к фотке "Лето" Эдиля Муратова';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Ваш коммент',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $commented_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_user_image_comment_emotion() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserImageCommentEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_user_image_comment_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_image_id = $this->input->post('user_image_id');
        $user_image_comment_id = $this->input->post('user_image_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_user_image_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'user_image_id' => $user_image_id,
            'user_image_comment_id' => $user_image_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->users_model->updateUserImageCommentEmotionById($id, $data_user_image_comment_emotions);
    }
}