<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_categories extends CI_Controller {

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

    public function insert_event_category() {
        $category_name = $this->input->post('category_name');

        $data_event_categories = array(
            'category_name' => $category_name,
        );
        $this->events_model->insertEventCategory($data_event_categories);
    }

    public function delete_event_category() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventCategory($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}