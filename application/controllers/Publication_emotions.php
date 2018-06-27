<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'publications' => $this->publications_model->getPublicationsByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_emotions', $data);
    }


    public function insert_publication_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_id = $this->input->post('publication_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'published_user_id' => $published_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_id' => $publication_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->insertPublicationEmotion($data_publication_emotions);

        $notification_text = 'Пользователь Назар оставил эмоцию на Вашу публикацию.';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Вашу публикацию',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $published_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_publication_emotion() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_publication_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_id = $this->input->post('publication_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'published_user_id' => $published_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_id' => $publication_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->updatePublicationEmotionById($id, $data_publication_emotions);
    }
}