<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'event_complaints' => $this->events_model->getEventComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_complaints', $data);
    }

    public function insert_event_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $event_id = $this->input->post('event_id');
        $complained_id = $this->input->post('complained_id');

        $data_event_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'event_id' => $event_id,
            'complained_id' => $complained_id
        );

        $this->events_model->insertEventComplaint($data_event_complaints);
    }
}