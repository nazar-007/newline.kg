<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'publications' => $this->publications_model->getPublications(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

    public function insert_friend() {
        $friend_date = date('d.m.Y');
        $friend_time = date('H:i:s');

        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');

        $data_friends_1 = array(
            'friend_date' => $friend_date,
            'friend_time' => $friend_time,
            'user_id' => $user_id,
            'friend_id' => $friend_id
        );

        $data_friends_2 = array(
            'friend_date' => $friend_date,
            'friend_time' => $friend_time,
            'user_id' => $friend_id,
            'friend_id' => $user_id
        );

        $this->users_model->insertFriend($data_friends_1);
        $this->users_model->insertFriend($data_friends_2);
    }
}