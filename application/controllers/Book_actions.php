<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'book_actions' => $this->books_model->getBookActionsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_actions', $data);
    }

}