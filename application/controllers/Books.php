<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
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
                'total_books' => $this->users_model->getTotalByFanUserIdAndFanTable($session_user_id, "book_fans"),
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

    public function get_one_book_by_admin() {
        $id = $this->input->post('id');
        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] || $_SESSION['admin_table']) {
            $one_book = $this->books_model->getOneBookById($id);
            $book_comments = $this->books_model->getBookCommentsByBookId($id);
            $html = '';
            foreach ($one_book as $info_book) {
                $book_id = $info_book->id;
                $book_file = $info_book->book_file;
                $html .= "<h3 class='centered'>$info_book->book_name</h3>
                    <div class='row'>
                        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                            <div>
                                <strong class='book_th'>Автор: </strong>
                                <span class='book_td'>$info_book->book_author</span>
                            </div>
                            <div>
                                <strong class='book_th'>Описание: </strong>
                                <span class='book_td'>$info_book->book_description</span>
                            </div>
                            <div>
                                <strong class='book_th'>Категория: </strong>
                                <span class='book_td'>$info_book->category_name</span>
                            </div>
                        </div>
                    </div>
                <div class='book-iframe'>
                    <iframe width='560' height='315' src='" . base_url() . "uploads/book_files/$book_file' frameborder='0'></iframe>
                </div>";
            }

            $html .= "<h3 class='centered'>Комменты к книге</h3>";

            if (count($book_comments) == 0) {
                $html .= 'Комментов к данной книге пока нет';
            } else {
                foreach ($book_comments as $book_comment) {
                    $html .= "<div class='one_comment_$book_comment->id'>
                        <div class='commented_user'>
                            <img src='" . base_url() . "uploads/images/user_images/" . $book_comment->main_image . "' class='commented_avatar'>
                            $book_comment->nickname $book_comment->surname 
                            <span class='comment-date-time'>$book_comment->comment_date <br> $book_comment->comment_time</span>
                            <div onclick='deleteBookCommentByAdmin(this)' data-book_comment_id='$book_comment->id' data-comment_text='$book_comment->comment_text' class='right'>X</div>
                        </div>
                    <div class='comment_text'>
                       $book_comment->comment_text
                    </div>
                </div>";
                    }
                }
            $get_json = array(
                'get_one_book' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $get_json = array(
                'get_error' => 'У вас нет прав на просмотр книги',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($get_json);
    }

    public function insert_book() {
        $book_name = $this->input->post('book_name');
        $book_file = $this->input->post('book_file');
        $book_author = $this->input->post('book_author');
        $book_description = $this->input->post('book_description');
        $book_image = $this->input->post('book_image');
        $category_id = $this->input->post('category_id');
        $suggested_user_id = $this->input->post('suggested_user_id');

        $data_books = array(
            'book_name' => $book_name,
            'book_file' => $book_file,
            'book_author' => $book_author,
            'book_description' => $book_description,
            'book_image' => $book_image,
            'category_id' => $category_id,
        );

        $this->books_model->insertBook($data_books);
        $insert_book_id = $this->db->insert_id();

        $user_name = $this->users_model->getNicknameAndSurnameById($suggested_user_id);
        $book_name = $this->books_model->getBookNameById($insert_book_id);

        $data_book_actions = array(
            'book_action' => "Предложенную книгу $book_name пользователя $user_name опубликовали админы.",
            'book_time_unix' => time(),
            'action_user_id' => $suggested_user_id,
            'book_id' => $insert_book_id
        );
        $this->books_model->insertBookAction($data_book_actions);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        $notification_text = "Админ одобрил Вашу предложенную книгу $book_name. К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.";

        $data_user_notifications = array(
            'notification_type' => 'Одобрение Вашей книги',
            'notification_text' => $notification_text,
            'notification_date' => $notification_date,
            'notification_time' => $notification_time,
            'notification_viewed' => 'Не просмотрено',
            'link_id' => $insert_book_id,
            'link_table' => 'books',
            'user_id' => $suggested_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before + 1,
                'rating' => $rating_before + 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);

            $suggestion_id = $this->input->post('suggestion_id');
            $this->books_model->deleteBookSuggestionById($suggestion_id);
        }

    public function delete_book() {
        $id = $this->input->post('id');
        $book_name = $this->input->post('book_name');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if (!$admin_id && !$admin_email && !$admin_table) {
            $delete_json = array(
                'book_error' => 'Не удалось удалить книгу',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
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

            $data_admin_actions = array(
                'admin_action' => "$admin_email удалил книгу $book_name под id $id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);

            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function update_book() {
        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] && $_SESSION['admin_table']) {
            $id = $this->input->post('id');
            $book_name = $this->input->post('book_name');
            $book_author = $this->input->post('book_author');
            $book_description = $this->input->post('book_description');
            $data_books = array(
                'book_name' => $book_name,
                'book_author' => $book_author,
                'book_description' => $book_description,
            );
            $this->books_model->updateBookById($id, $data_books);

            $update_json = array(
                'id' => $id,
                'book_name' => $book_name,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $update_json = array(
                'update_error' => 'Не удалось сохранить изменения',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($update_json);
    }

}