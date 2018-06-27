<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_comment_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'event_comment_complaints' => $this->events_model->getEventCommentComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_comment_complaints', $data);
    }

    public function insert_event_comment_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $event_id = $this->input->post('event_id');
        $event_comment_id = $this->input->post('event_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_event_comment_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'event_id' => $event_id,
            'event_comment_id' => $event_comment_id,
            'commented_user_id' => $commented_user_id,
            'complained_user_id' => $complained_user_id
        );

        $this->events_model->insertEventCommentComplaint($data_event_comment_complaints);
    }

    public function delete_event_comment_complaint() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventCommentComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_event_comment_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->events_model->deleteEventCommentComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}