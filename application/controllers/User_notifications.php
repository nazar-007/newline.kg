<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = 1;
        $data = array(
            'albums' => $this->albums_model->getAlbums($user_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('albums', $data);
    }

    public function delete_user_notification() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserNotificationByUd($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_user_notifications_by_user_id() {
        $user_id = $this->input->post('user_id');
        $this->users_model->deleteUserNotificationsByUserId($user_id);
        $delete_json = array(
            'user_id' => $user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}