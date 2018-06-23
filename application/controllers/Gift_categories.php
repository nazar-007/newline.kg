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



}