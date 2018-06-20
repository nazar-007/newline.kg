<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooks($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('books', $data);
    }

    public function insert_book_fan() {
        $fan_date = date('d.m.Y');
        $fan_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $book_id = $this->input->post('book_id');

        $data_book_fans = array(
            'fan_date' => $fan_date,
            'fan_time' => $fan_time,
            'user_id' => $user_id,
            'book_id' => $book_id,
        );
        $this->books_model->insertBookFan($data_book_fans);
    }

    public function delete_book_fan() {
//        $id = $this->input->post('id');
        $id = 22;
        $this->books_model->deleteBookFan($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }


}