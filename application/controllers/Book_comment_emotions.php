<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_comment_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooksByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_comment_emotions', $data);
    }

    public function insert_book_comment_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $book_id = $this->input->post('book_id');
        $book_comment_id = $this->input->post('book_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_book_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'book_id' => $book_id,
            'book_comment_id' => $book_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->books_model->insertBookCommentEmotion($data_book_comment_emotions);

        $notification_text = 'Пользователь Назар поставил эмоцию на Ваш коммент "Супер!" к книге "Убить пересмешника"';

        $data_user_notifications = array(
            'notification_type' => 'Эмоция на Ваш коммент',
            'notification_text' => $notification_text,
            'notification_date' => $emotion_date,
            'notification_time' => $emotion_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $commented_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_book_comment_emotion() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookCommentEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_comment_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $book_id = $this->input->post('book_id');
        $book_comment_id = $this->input->post('book_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_book_comment_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'book_id' => $book_id,
            'book_comment_id' => $book_comment_id,
            'commented_user_id' => $commented_user_id,
            'emotion_id' => $emotion_id
        );
        $this->books_model->updateBookCommentEmotionById($id, $data_book_comment_emotions);
    }
}