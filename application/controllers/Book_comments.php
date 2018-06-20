<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $book_id = 1;
        $data = array(
            'book_comments' => $this->books_model->getBookComments($book_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_comments', $data);
    }

    public function insert_book_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $book_id = $this->input->post('book_id');

        $data_book_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'user_id' => $user_id,
            'book_id' => $book_id
        );
        $this->books_model->insertBookComment($data_book_comments);
    }
}