<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('emotions_model');
    }

    public function Index() {
        $data = array(
            'emotions' => $this->emotions_model->getEmotions(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('emotions', $data);
    }


}