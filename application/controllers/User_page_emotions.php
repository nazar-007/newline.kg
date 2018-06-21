<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_page_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'countries' => $this->countries_model->getCountries(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_page_emotions', $data);
    }

    public function insert_user_page_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $user_id = $this->input->post('user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_user_page_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'user_id' => $user_id,
            'emotion_id' => $emotion_id
        );
        $this->users_model->insertUserPageEmotion($data_user_page_emotions);

        $notification_text = 'Пользователь Назар оставил эмоцию на Ващу страницу';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Вашу страницу',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_user_page_emotion() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserPageEmotion($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}