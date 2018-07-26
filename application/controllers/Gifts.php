<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gifts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $category_ids = array();
        $user_id = $_SESSION['user_id'];
        $data_gifts = array(
            'gifts' => $this->gifts_model->getGiftsByCategoryIds($category_ids),
            'my_gifts' => $this->gifts_model->getGiftSentByUserId($user_id),
            'friends' => $this->users_model->getFriendsByUserId($user_id),
            'gift_categories' => $this->gifts_model->getGiftCategories(),
            'currency' => $this->users_model->getCurrencyById($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('gifts', $data_gifts);
    }

    public function insert_gift() {
        $gift_name = $this->input->post('gift_name');
        $gift_file = $this->input->post('gift_file');
        $gift_price = $this->input->post('gift_price');
        $category_id = $this->input->post('category_id');

        $data_gifts = array(
            'gift_name' => $gift_name,
            'gift_file' => $gift_file,
            'gift_price' => $gift_price,
            'category_id' => $category_id
        );
        $this->gifts_model->insertGift($data_gifts);
    }

    public function delete_gift() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_gift() {
        $id = $this->input->post('id');
        $gift_name = $this->input->post('gift_name');
        $gift_file = $this->input->post('gift_file');
        $category_id = $this->input->post('category_id');

        $data_gifts = array(
            'gift_name' => $gift_name,
            'gift_file' => $gift_file,
            'category_id' => $category_id
        );
        $this->gifts_model->updateGiftById($id, $data_gifts);
    }
}