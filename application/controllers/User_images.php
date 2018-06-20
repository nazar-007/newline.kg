<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_images extends CI_Controller {

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

    public function insert_album() {
        $data = array();
        $this->albums_model->insertAlbum($data);
    }
}