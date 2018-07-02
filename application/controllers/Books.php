<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooksByCategoryIds($category_ids),
            'book_categories' => $this->books_model->getBookCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('books', $data);
    }

    public function choose_book_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $books = $this->books_model->getBooksByCategoryIds($category_ids);
        foreach ($books as $book) {
            echo "<tr>
            <td>$book->id</td>
            <td>
                <a href='" . base_url() . "models/" . $book->id . "'>" . $book->book_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_book() {
        $book_name = $this->input->post('book_name');
        $book_file = $this->input->post('book_file');
        $book_author = $this->input->post('book_author');
        $book_description = $this->input->post('book_description');
        $book_image = $this->input->post('book_image');
        $book_year = $this->input->post('book_year');
        $book_http_link = $this->input->post('book_http_link');
        $category_id = $this->input->post('category_id');
        $user_id = $this->input->post('suggested_user_id');

        $data_books = array(
            'book_name' => $book_name,
            'book_file' => $book_file,
            'book_author' => $book_author,
            'book_description' => $book_description,
            'book_image' => $book_image,
            'book_year' => $book_year,
            'book_http_link' => $book_http_link,
            'category_id' => $category_id,
            'user_id' => $user_id
        );

        $this->books_model->insertBook($data_books);
        $insert_book_id = $this->db->insert_id();

        // НАДО ДОДЕЛАТЬ ЭКШН

        $book_action = 'Предложенную книгу Назара "Убить пересмешника" одобрили админы.';
        $data_book_actions = array(
            'book_action' => $book_action,
            'book_time_unix' => time(),
            'action_user_id' => $user_id,
            'book_id' => $insert_book_id
        );
        $this->books_model->insertBookAction($data_book_actions);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        if ($user_id != 0) {
            $notification_text = 'Админ одобрил Вашу книгу "Убить пересмешника". К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашей книги',
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
    }

    public function delete_book() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $book_file = $this->books_model->getBookFileById($id);
        unlink("./uploads/book_files/$book_file");

        $book_comments = $this->books_model->getBookCommentsByBookId($id);
        foreach ($book_comments as $book_comment) {
            $book_comment_id = $book_comment->id;
            $this->books_model->deleteBookCommentComplaintsByBookCommentId($book_comment_id);
            $this->books_model->deleteBookCommentEmotionsByBookCommentId($book_comment_id);
        }
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