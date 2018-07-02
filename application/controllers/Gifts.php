<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gifts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'gifts' => $this->gifts_model->getGiftsByCategoryIds($category_ids),
            'gift_categories' => $this->gifts_model->getGiftCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('gifts', $data);
    }

    public function choose_gift_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $books = $this->gifts_model->getGiftsByCategoryIds($category_ids);
        foreach ($books as $book) {
            echo "<tr>
            <td>$book->id</td>
            <td>
                <a href='" . base_url() . "models/" . $book->id . "'>" . $book->book_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_gift() {
        $gift_name = $this->input->post('gift_name');
        $gift_file = $this->input->post('gift_file');
        $category_id = $this->input->post('category_id');

        $data_gifts = array(
            'gift_name' => $gift_name,
            'gift_file' => $gift_file,
            'category_id' => $category_id
        );
        $this->gifts_model->insertGift($data_gifts);
    }

    public function delete_gift() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
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