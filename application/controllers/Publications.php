<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'publications' => $this->publications_model->getPublicationsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

}