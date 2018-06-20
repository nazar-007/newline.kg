<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->events_model->getEvents($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_categories', $data);
    }

    public function insert_book_category() {
        $category_name = $this->input->post('category_name');

        $data_book_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->insertBookCategory($data_book_categories);
    }

    public function delete_book_category() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookCategory($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}