<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Private_messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('messages_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooks($category_ids),
            'book_categories' => $this->books_model->getBookCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('books', $data);
    }

    public function get_messages() {
        $user_id = 1;
        $talker_id = 2;

        $this->messages_model->getPrivateMessagesByUserIdAndTalkerId($user_id, $talker_id);

    }



}