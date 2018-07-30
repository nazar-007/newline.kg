<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $session_user_id = $_SESSION['user_id'];
        $friend_ids = array();
        $friends = $this->users_model->getFriendsByUserId($session_user_id);
        foreach ($friends as $friend) {
            $friend_ids[] = $friend->friend_id;
        }
        $html = '';
        if (count($friend_ids) == 0) {
            $html .= "<h4 class='centered'>Здесь появятся действия Ваших друзей с книгами</h4>";
        } else {
            $book_actions = $this->books_model->getBookActionsByFriendIds($friend_ids);
            foreach ($book_actions as $book_action) {
                $html .= "<div class='action-info'>
                        <img class='action-image' src='" . base_url() . "uploads/images/book_images/$book_action->book_image'>
                        <span class='action-text'>
                            $book_action->book_action <br>
                            <a href='" . base_url() . "one_book/$book_action->book_id'>Смотреть</a>
                        </span><hr>
                    </div>";
            }
        }
        $data = array(
            'book_actions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);

    }
}