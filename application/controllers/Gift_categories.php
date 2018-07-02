<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'gifts' => $this->gifts_model->getGiftsByCategoryIds($category_ids),
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
        $gifts = $this->gifts_model->getGiftsByCategoryIds($category_ids);
        foreach ($gifts as $gift) {
            echo "<tr>
            <td>$gift->id</td>
            <td>
                <a href='" . base_url() . "models/" . $gift->id . "'>" . $gift->gift_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_gift_category() {
        $category_name = $this->input->post('category_name');

        $data_gift_categories = array(
            'category_name' => $category_name,
        );
        $this->gifts_model->insertGiftCategory($data_gift_categories);
    }

    public function delete_gift_category() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_gift_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_gift_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->updateGiftCategoryById($id, $data_gift_categories);
    }

}