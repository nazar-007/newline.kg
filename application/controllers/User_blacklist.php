<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_blacklist extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = 1;
        $data = array (
            'user_blacklist' => $this->users_model->getUserBlacklistByUserId($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_blacklist', $data);
    }

    public function insert_user_blacklist() {
        $black_reason = $this->input->post('black_reason');
        $black_date = date('d.m.Y');
        $black_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $black_user_id = $this->input->post('black_user_id');

        $data_user_blacklist = array(
            'black_reason' => $black_reason,
            'black_date' => $black_date,
            'black_time' => $black_time,
            'user_id' => $user_id,
            'black_user_id' => $black_user_id
        );
        $this->users_model->insertUserBlacklist($data_user_blacklist);
    }

    public function delete_user_blacklist() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserBlacklistById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}