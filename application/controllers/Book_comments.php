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
            'book_comments' => $this->books_model->getBookCommentsByBookId($book_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_comments', $data);
    }

    public function insert_book_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $commented_user_id = $this->input->post('commented_user_id');
        $book_id = $this->input->post('book_id');

        $data_book_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'commented_user_id' => $commented_user_id,
            'book_id' => $book_id
        );
        $this->books_model->insertBookComment($data_book_comments);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $book_action = 'Пользователь Назар прокомментировал книгу "Убить пересмешника".';

        $data_book_actions = array(
            'book_action' => $book_action,
            'book_time_unix' => time(),
            'action_user_id' => $commented_user_id,
            'book_id' => $book_id
        );
        $this->books_model->insertBookAction($data_book_actions);
    }

    public function delete_book_comment() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookCommentById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_comment() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');

        $data_book_comments = array(
            'comment_text' => $comment_text
        );
        $this->books_model->updateBookCommentById($id, $data_book_comments);
    }
}