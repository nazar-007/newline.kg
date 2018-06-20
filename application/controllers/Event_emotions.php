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
            'events' => $this->events_model->getEvents($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_emotions', $data);
    }

    public function insert_event_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $event_id = $this->input->post('event_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_event_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'event_id' => $event_id,
            'emotion_id' => $emotion_id
        );
        $this->events_model->insertEventEmotion($data_event_emotions);
    }

    public function delete_event_emotion() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventEmotion($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}