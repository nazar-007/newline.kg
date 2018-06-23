<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooksByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_emotions', $data);
    }

    public function insert_book_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $book_id = $this->input->post('book_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_book_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'book_id' => $book_id,
            'emotion_id' => $emotion_id
        );
        $this->books_model->insertBookEmotion($data_book_emotions);
    }

    public function delete_book_emotion() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $book_id = $this->input->post('book_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_book_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'book_id' => $book_id,
            'emotion_id' => $emotion_id
        );
        $this->books_model->updateBookEmotionById($id, $data_book_emotions);
    }
}