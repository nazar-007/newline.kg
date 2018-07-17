<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEventsByCategoryIds($category_ids),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_emotions', $data);
    }

    public function insert_event_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $event_id = $this->input->post('event_id');

        $data_event_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventEmotion($data_event_emotions);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $event_action = 'Пользователь Назар поставил эмоцию на событие "Встреча крутых IT-специалистов"';

        $data_event_actions = array(
            'event_action' => $event_action,
            'event_time' => time(),
            'action_user_id' => $emotioned_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventAction($data_event_actions);
    }

    public function delete_event_emotion() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}