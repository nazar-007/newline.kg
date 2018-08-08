<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guests extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = $_SESSION['user_id'];
        $data = array(
            'guests' => $this->users_model->getGuestsByUserId($user_id),
            'users' => $this->users_model->getUsersByGuestId($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('guests', $data);
    }

    public function insert_guest() {
        $guest_date = date('d.m.Y');
        $guest_time = date('H:i:s');
        $guest_time_unix = time();
        $user_viewed = 'not viewed';
        $user_id = $this->input->post('user_id');
        $guest_id = $this->input->post('guest_id');

        $data_guests = array(
            'guest_date' => $guest_date,
            'guest_time' => $guest_time,
            'guest_time_unix' => $guest_time_unix,
            'user_viewed' => $user_viewed,
            'user_id' => $user_id,
            'guest_id' => $guest_id
        );
        $this->users_model->insertGuest($data_guests);
    }

    public function update_guest() {
        $id = $this->input->post('id');
        $guest_date = date('d.m.Y');
        $guest_time = date('H:i:s');
        $guest_time_unix = time();
        $user_viewed = 'not viewed';
        $user_id = $this->input->post('user_id');
        $guest_id = $this->input->post('guest_id');

        $data_guests = array(
            'guest_date' => $guest_date,
            'guest_time' => $guest_time,
            'guest_time_unix' => $guest_time_unix,
            'user_viewed' => $user_viewed,
            'user_id' => $user_id,
            'guest_id' => $guest_id
        );
        $this->users_model->updateGuestById($id, $data_guests);
    }

}