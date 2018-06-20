<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_fans extends CI_Controller {

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
        $this->load->view('events', $data);
    }

    public function insert_event_fan() {
        $fan_date = date('d.m.Y');
        $fan_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $event_id = $this->input->post('event_id');

        $data_event_fans = array(
            'fan_date' => $fan_date,
            'fan_time' => $fan_time,
            'user_id' => $user_id,
            'event_id' => $event_id,
        );
        $this->events_model->insertEventFan($data_event_fans);
    }

    public function delete_event_fan() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventFan($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}