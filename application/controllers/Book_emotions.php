<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $book_id = $this->input->post('book_id');
        $book_emotions = $this->books_model->getBookEmotionsByBookId($book_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($book_emotions) == 0 ) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($book_emotions as $book_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$book_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$book_emotion->main_image' class='action_avatar'>
                            </div>
                            <div class='emotion_user_name'>
                                $book_emotion->nickname $book_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_book_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }

    public function insert_book_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $book_id = $this->input->post('book_id');
        $emotion_num_rows = $this->books_model->getBookEmotionNumRowsByBookIdAndEmotionedUserId($book_id, $emotioned_user_id);

        if ($emotion_num_rows == 0 && $emotioned_user_id == $session_user_id) {
            $data_book_emotions = array(
                'emotion_date' => $emotion_date,
                'emotion_time' => $emotion_time,
                'emotioned_user_id' => $emotioned_user_id,
                'book_id' => $book_id
            );
            $this->books_model->insertBookEmotion($data_book_emotions);

            $user_name = $this->users_model->getNicknameAndSurnameById($emotioned_user_id);
            $book_name = $this->books_model->getBookNameById($book_id);

            $data_book_actions = array(
                'book_action' => "$user_name поставил(-а) эмоцию на книгу '$book_name'",
                'book_time_unix' => time(),
                'action_user_id' => $emotioned_user_id,
                'book_id' => $book_id
            );
            $this->books_model->insertBookAction($data_book_actions);

            $total_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы уже ставили эмоцию на данную книгу или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_book_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $book_id = $this->input->post('book_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->books_model->getBookEmotionNumRowsByBookIdAndEmotionedUserId($book_id, $emotioned_user_id);

        if ($emotion_num_rows > 0 && $emotioned_user_id == $session_user_id) {
            $this->books_model->deleteBookEmotionByBookIdAndEmotionedUserId($book_id, $emotioned_user_id);
            $total_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на данную книгу или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}