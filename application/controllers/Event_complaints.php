<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'event_complaints' => $this->events_model->getEventComplaintsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_complaints', $data);
    }

    public function insert_event_complaint() {
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('events');
        $event_id = $this->input->post('event_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->events_model->getEventComplaintNumRowsByEventIdAndComplainedUserId($event_id, $complained_user_id);
        if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
            $data_book_complaints = array(
                'complaint_text' => $complaint_text,
                'complaint_time_unix' => $complaint_time_unix,
                'admin_id' => $admin_id,
                'event_id' => $event_id,
                'complained_user_id' => $complained_user_id
            );
            $this->events_model->insertEventComplaint($data_book_complaints);
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                'event_id' => $event_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную книгу, или текст жалобы пуст, или что-то пошло не так.",
                'event_id' => $event_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_event_complaint() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_event_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->events_model->deleteEventComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event_complaint() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_event_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->events_model->updateBookComplaintById($id, $data_event_complaints);
    }
}