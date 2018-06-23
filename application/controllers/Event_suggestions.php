<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'event_suggestions' => $this->events_model->getEventSuggestionsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_suggestions', $data);
    }

    public function insert_event_suggestion() {
        $suggestion_json = $this->input->post('suggestion_json');
        $suggestion_file = $this->input->post('suggestion_file');
        $suggestion_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $user_id = $this->input->post('user_id');

        $data_event_suggestions = array(
            'suggestion_json' => $suggestion_json,
            'suggestion_file' => $suggestion_file,
            'suggestion_time_unix' => $suggestion_time_unix,
            'admin_id' => $admin_id,
            'user_id' => $user_id
        );
        $this->events_model->insertEventSuggestion($data_event_suggestions);
    }

    public function delete_event_suggestion() {
        // надо удалять файлы тоже

//        $id = $this->input->post('id');
//        $this->users_model->deleteEventSuggestionById($id);
//        $delete_json = array(
//            'id' => $id,
//            'csrf_name' => $this->security->get_csrf_token_name (),
//            'csrf_hash' => $this->security->get_csrf_hash()
//        );
//        echo json_encode($delete_json);
    }

}