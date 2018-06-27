<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_comment_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEventsByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_comment_emotions', $data);
    }

    public function insert_event_comment_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $event_id = $this->input->post('event_id');
        $event_comment_id = $this->input->post('event_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_event_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'event_id' => $event_id,
            'event_comment_id' => $event_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->events_model->insertEventCommentEmotion($data_event_comment_emotions);

        $notification_text = 'Пользователь Назар поставил эмоцию на Ваш коммент "Супер!" к событию "Встреча крутых IT-специалистов"';

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

    public function delete_event_comment_emotion() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventCommentEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event_comment_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $event_id = $this->input->post('event_id');
        $event_comment_id = $this->input->post('event_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_event_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'event_id' => $event_id,
            'event_comment_id' => $event_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->events_model->updateEventCommentEmotionById($id, $data_event_comment_emotions);
    }
}