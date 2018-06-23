<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'user_image_actions' => $this->publications_model->getUserImageActionsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_image_actions', $data);
    }


}