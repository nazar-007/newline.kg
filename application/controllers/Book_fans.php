<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $book_id = $this->input->post('book_id');
        $book_fans = $this->books_model->getBookFansByBookId($book_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($book_fans) == 0) {
            $html .= "<h3 class='centered'>Пока никто не добавлял это в любимки.</h3>";
        } else {
            foreach ($book_fans as $book_fan) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 fan_user'>
                        <a href='" . base_url() . "one_user/$book_fan->email'>
                            <div class='fan_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$book_fan->main_image' class='action_avatar'>
                            </div>
                            <div class='fan_user_name'>
                                $book_fan->nickname $book_fan->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_fans_json = array(
            'one_book_fans' => $html,
            'my_fan_books' => $this->books_model->getBookFansByFanUserId($session_user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_fans_json);
    }

    public function common_books() {
        $this->load->view('session_user');
        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');
        $common_books = $this->books_model->getCommonBooksByTwoUsers($user_id, $friend_id);
        $html = '';
        foreach ($common_books as $common_book) {
            $html .= "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$common_book->book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$common_book->book_image'>
                        </div>
                        <div class='book_name'>$common_book->book_name</div>
                    </a>
                </div>";
        }
        $get_common_json = array(
            'common_books' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_common_json);

    }

    public function insert_book_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $fan_date = date("d.m.Y");
        $fan_time = date("H:i:s");
        $fan_user_id = $this->input->post('fan_user_id');
        $book_id = $this->input->post('book_id');

        $fan_num_rows = $this->books_model->getBookFanNumRowsByBookIdAndFanUserId($book_id, $fan_user_id);

        if ($fan_num_rows == 0 && $fan_user_id == $session_user_id) {
            $data_book_fans = array(
                'fan_date' => $fan_date,
                'fan_time' => $fan_time,
                'fan_user_id' => $fan_user_id,
                'book_id' => $book_id
            );
            $this->books_model->insertBookFan($data_book_fans);

            $user_name = $this->users_model->getNicknameAndSurnameById($fan_user_id);
            $book_name = $this->books_model->getBookNameById($book_id);

            $data_book_actions = array(
                'book_action' => "$user_name добавил в свои любимки книгу '$book_name'",
                'book_time_unix' => time(),
                'action_user_id' => $fan_user_id,
                'book_id' => $book_id
            );
            $this->books_model->insertBookAction($data_book_actions);

            $total_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы уже добавляли эту книгу в любимки или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_book_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $book_id = $this->input->post('book_id');
        $fan_user_id = $this->input->post('fan_user_id');
        $fan_num_rows = $this->books_model->getBookFanNumRowsByBookIdAndFanUserId($book_id, $fan_user_id);

        if ($fan_num_rows > 0 && $fan_user_id == $session_user_id) {
            $this->books_model->deleteBookFanByBookIdAndFanUserId($book_id, $fan_user_id);
            $total_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы ещё не добавляли данную книгу в любимки или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }


}