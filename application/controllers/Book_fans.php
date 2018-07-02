<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $book_id = 1;
        $data = array(
            'book_fans' => $this->books_model->getBookFansByBookId($book_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_fans', $data);
    }

    public function insert_book_fan() {
        $fan_date = date('d.m.Y');
        $fan_time = date('H:i:s');
        $fan_user_id = $this->input->post('fan_user_id');
        $book_id = $this->input->post('book_id');

        $data_book_fans = array(
            'fan_date' => $fan_date,
            'fan_time' => $fan_time,
            'fan_user_id' => $fan_user_id,
            'book_id' => $book_id
        );
        $this->books_model->insertBookFan($data_book_fans);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $book_action = 'Пользователь Назар добавил книгу "Убить пересмешника" в свою библиотеку';

        $data_book_actions = array(
            'book_action' => $book_action,
            'book_time_unix' => time(),
            'action_user_id' => $fan_user_id,
            'book_id' => $book_id
        );
        $this->books_model->insertBookAction($data_book_actions);
    }

    public function delete_book_fan() {
        // НАДО ДОДЕЛАТЬ!!!
        $id = $this->input->post('id');
        $this->books_model->deleteBookFanById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }


}