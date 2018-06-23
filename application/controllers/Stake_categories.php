<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stake_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'stakes' => $this->stakes_model->getStakesByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('stakes', $data);
    }
}