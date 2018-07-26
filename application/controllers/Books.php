<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $category_ids = array();
        if (isset($_POST['offset'])) {
            $offset = $this->input->post('offset');
        } else {
            $offset = 0;
        }

        $books = $this->books_model->getBooksByCategoryIds($category_ids, $offset);
        $html = '';
        foreach ($books as $book) {
            $book_id = $book->id;
            $book_name = $book->book_name;
            $total_book_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
            $total_book_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
            $html .= "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$book->book_image'>             
                        </div>
                        <div class='book_name'>$book_name</div>
                    </a>
                    <div class='actions'>
                        <span class='emotions_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                            <span class='badge' onclick='getBookEmotions(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookEmotions'>$total_book_emotions</span>
                        </span>
                        <span class='fans_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                            <span class='badge' onclick='getBookFans(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookFans'>$total_book_fans</span>
                        </span>
                    </div>
                </div>";
        }

        if (isset($_POST['offset'])) {
            $data = array(
                'books' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            echo json_encode($data);
        } else {

            $friend_ids = array();
            $friends = $this->users_model->getFriendsByUserId($session_user_id);
            foreach ($friends as $friend) {
                $friend_ids[] = $friend->friend_id;
            }

            $data = array(
                'books' => $html,
                'friend_ids' => $friend_ids,
                'book_actions' => $this->books_model->getBookActionsByFriendIds($friend_ids),
                'book_categories' => $this->books_model->getBookCategories(),
                'my_fan_books' => $this->books_model->getBookFansByFanUserId($session_user_id),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('books', $data);
        }
    }

    public function One_book($id) {
        $book_num_rows = $this->books_model->getBookNumRowsById($id);
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        if ($book_num_rows == 1) {
            $data_books = array(
                'current_id' => $id,
                'one_book' => $this->books_model->getOneBookById($id),
                'complaint_num_rows' => $this->books_model->getBookComplaintNumRowsByBookIdAndComplainedUserId($id, $session_user_id),
                'emotion_num_rows' => $this->books_model->getBookEmotionNumRowsByBookIdAndEmotionedUserId($id, $session_user_id),
                'fan_num_rows' => $this->books_model->getBookFanNumRowsByBookIdAndFanUserId($id, $session_user_id),
                'total_emotions' => $this->books_model->getTotalByBookIdAndBookTable($id, 'book_emotions'),
                'total_comments' => $this->books_model->getTotalByBookIdAndBookTable($id, 'book_comments'),
                'total_fans' => $this->books_model->getTotalByBookIdAndBookTable($id, 'book_fans'),
                'book_num_rows' => $book_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_books = array(
                'current_id' => $id,
                'book_num_rows' => $book_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('one_book', $data_books);
    }

    public function download_book() {
        $id = $this->input->get('id');
        $book_num_rows = $this->books_model->getBookNumRowsById($id);

        if ($book_num_rows > 0) {
            $book_file = "uploads/book_files/" . $this->books_model->getBookFileById($id);
            $this->load->helper("download");
            force_download($book_file, NULL);
        } else {
            echo "Не удалось скачать книгу.:(";
        }
    }

    public function search_books() {
        $search_by_name = $this->input->post('search_by_name');
        $html = '';
        if (iconv_strlen($search_by_name) > 0) {
            $html .= "<h3 class='centered'>Результаты по запросу $search_by_name</h3>";
            $books = $this->books_model->searchBooksByBookName($search_by_name);
            if (count($books) == 0) {
                $html .= "<div class='red centered'>По Вашему запросу $search_by_name ничего не найдено! :(</div>";
            }
        } else {
            $html .= "<h3 class='centered'>Все книги</h3>";
            $books = $this->books_model->getBooksByCategoryIds(array(), 0);
        }
        foreach ($books as $book) {
            $book_id = $book->id;
            $book_name = $book->book_name;
            $total_book_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
            $total_book_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
            $html .= "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$book->book_image'>             
                        </div>
                        <div class='book_name'>$book_name</div>
                    </a>
                    <div class='actions'>
                        <span class='emotions_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                            <span class='badge' onclick='getBookEmotions(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookEmotions'>$total_book_emotions</span>
                        </span>
                        <span class='fans_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                            <span class='badge' onclick='getBookFans(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookFans'>$total_book_fans</span>
                        </span>
                    </div>
                </div>";
        }
        $data = array(
            'search_books' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_book() {
        $config['upload_path'] = './uploads/song_files';
        $config['allowed_types'] = 'pdf';
        $book_file = preg_replace('/[ \t]+/', '_', $_FILES['book_file']['name']);
        $config['file_name'] = $book_file;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('book_file')) {
            echo 'Файл успешно загружен!';
        } else {
            echo 'НЕ УДАЛОСЬ ЗАГРУЗИТЬ ФАЙЛ!';
        }

//        $book_name = "To kill the Mockingbird";
//        $book_author = 'Harper Li';
//        $book_description = 'lorem ipsum';
//        $book_image = 'aliceinwonder.jpg';
//        $book_year = 2015;
//        $book_http_link = 'http://google.com';
//        $category_id = 2;
//        $user_id = 1;
//
//        $data_books = array(
//            'book_name' => $book_name,
//            'book_file' => $book_file,
//            'book_author' => $book_author,
//            'book_description' => $book_description,
//            'book_image' => $book_image,
//            'book_year' => $book_year,
//            'book_http_link' => $book_http_link,
//            'category_id' => $category_id,
//            'user_id' => $user_id
//        );
//
//        $this->books_model->insertBook($data_books);
//        $insert_book_id = $this->db->insert_id();
//
//        // НАДО ДОДЕЛАТЬ ЭКШН
//
//        $book_action = 'Предложенную книгу Назара "Убить пересмешника" одобрили админы.';
//        $data_book_actions = array(
//            'book_action' => $book_action,
//            'book_time_unix' => time(),
//            'action_user_id' => $user_id,
//            'book_id' => $insert_book_id
//        );
//        $this->books_model->insertBookAction($data_book_actions);
//
//        $notification_date = date('d.m.Y');
//        $notification_time = date('H:i:s');
//        if ($user_id != 0) {
//            $notification_text = 'Админ одобрил Вашу книгу "Убить пересмешника". К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.';
//
//            $data_user_notifications = array(
//                'notification_type' => 'Одобрение Вашей книги',
//                'notification_text' => $notification_text,
//                'notification_date' => $notification_date,
//                'notification_time' => $notification_time,
//                'notification_viewed' => 'Не просмотрено',
//                'link_id' => $insert_book_id,
//                'link_table' => 'books',
//                'user_id' => $user_id
//            );
//            $this->users_model->insertUserNotification($data_user_notifications);
//
//            $currency_before = $this->users_model->getCurrencyById($user_id);
//            $rating_before = $this->users_model->getRatingById($user_id);
//
//            $data_users = array(
//                'currency' => $currency_before + 1,
//                'rating' => $rating_before + 5
//            );
//            $this->users_model->updateUserById($user_id, $data_users);
//
//            $rating_after = $this->users_model->getRatingById($user_id);
//            $rank_after = $this->users_model->getRankById($user_id);
//            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);
//
//            $suggestion_id = $this->input->post('suggestion_id');
//            $this->books_model->deleteBookSuggestionById($suggestion_id);
//        }
    }

    public function delete_book() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $book_file = $this->books_model->getBookFileById($id);
        $book_image = $this->books_model->getBookImageById($id);
        unlink("./uploads/book_files/$book_file");
        unlink("./uploads/images/book_images/$book_image");

        $this->books_model->deleteBookActionsByBookId($id);
        $this->books_model->deleteBookCommentsByBookId($id);
        $this->books_model->deleteBookComplaintsByBookId($id);
        $this->books_model->deleteBookEmotionsByBookId($id);
        $this->books_model->deleteBookFansByBookId($id);
        $this->books_model->deleteBookById($id);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        if ($user_id != 0) {
            $notification_text = 'Ваша одобренная книга "Убить пересмешника" удалена. С Вашей валюты снялся 1 сом, а с рейтинга - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Удаление Вашей одобренной книги',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before - 1,
                'rating' => $rating_before - 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);
        }

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book() {
        $id = $this->input->post('id');
        $book_name = $this->input->post('book_name');
        $book_author = $this->input->post('book_author');
        $book_description = $this->input->post('book_description');
        $book_year = $this->input->post('book_year');
        $book_http_link = $this->input->post('book_http_link');

        $data_books = array(
            'book_name' => $book_name,
            'book_author' => $book_author,
            'book_description' => $book_description,
            'book_year' => $book_year,
            'book_http_link' => $book_http_link
        );

        $this->books_model->updateBookById($id, $data_books);
    }

}