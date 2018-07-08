<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_share_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'publications' => $this->publications_model->getPublicationsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

    public function insert_publication_share_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $share_user_id = $this->input->post('share_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_share_id = $this->input->post('publication_share_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_share_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'share_user_id' => $share_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_share_id' => $publication_share_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->insertPublicationShareEmotion($data_publication_share_emotions);

        $notification_text = 'Пользователь Назар оставил эмоцию на публикацию, которой Вы поделились.';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на публикацию, которой поделились',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $published_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_publication_share_emotion() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationShareEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_publication_share_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_id = $this->input->post('publication_id');
        $publication_share_id = $this->input->post('publication_share_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'published_user_id' => $published_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_id' => $publication_id,
            'publication_share_id' => $publication_share_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->updatePublicationShareEmotionById($id, $data_publication_image_emotions);
    }
}