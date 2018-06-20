<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_sent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'gifts' => $this->gifts_model->getGifts($category_ids),
            'gift_categories' => $this->gifts_model->getGiftCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('gift_sent', $data);
    }

    public function delete_gift_sent() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftSent($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}