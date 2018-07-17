<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'user_complaints' => $this->users_model->getUserComplaintsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_complaints', $data);
    }

    public function insert_user_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('users');
        $user_id = $this->input->post('user_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_user_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'user_id' => $user_id,
            'complained_user_id' => $complained_user_id
        );

        $this->users_model->insertUserComplaint($data_user_complaints);
    }

    public function delete_user_complaint() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_user_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->users_model->deleteUserComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_user_complaint() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_user_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->users_model->updateUserComplaintById($id, $data_user_complaints);
    }

}