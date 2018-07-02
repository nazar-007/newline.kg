<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'book_suggestions' => $this->books_model->getBookSuggestionsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_suggestions', $data);
    }

    public function insert_book_suggestion() {
        $book_name = $this->input->post('book_name');
        $book_author = $this->input->post('book_author');
        $book_description = $this->input->post('book_description');
        $book_year = $this->input->post('book_year');
        $book_http_link = $this->input->post('book_http_link');
        $category_id = $this->input->post('category_id');
        $suggestion_json = "[{'book_name': '$book_name', 'book_author': '$book_author', 'book_description': '$book_description', 
            'book_year': '$book_year', 'book_http_link': '$book_http_link', 'category_id': '$category_id'}]";
        $book_file = $this->input->post('book_file');
        $book_image = $this->input->post('book_image');
        $suggestion_date = date('d.m.Y');
        $suggestion_time = date('H:i:s');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('books');
        $suggested_user_id = $this->input->post('suggested_user_id');

        $data_book_suggestions = array(
            'suggestion_json' => $suggestion_json,
            'suggestion_file' => $book_file,
            'suggestion_image' => $book_image,
            'suggestion_date' => $suggestion_date,
            'suggestion_time' => $suggestion_time,
            'admin_id' => $admin_id,
            'suggestion_user_id' => $suggested_user_id
        );
        $this->books_model->insertBookSuggestion($data_book_suggestions);

    }

    public function delete_book_suggestion() {
        $id = $this->input->post('id');
        $book_suggestion_file = $this->books_model->getBookSuggestionFileById($id);
        $book_suggestion_image = $this->books_model->getBookSuggestionImageById($id);
        $book_name = $this->input->post('book_name');
        $user_id = $this->input->post('user_id');
        unlink("./uploads/book_files/$book_suggestion_file");
        unlink("./uploads/images/book_images/$book_suggestion_image");
        unlink("./uploads/images/book_images/thumb/$book_suggestion_image");
        $this->books_model->deleteBookSuggestionById($id);
        $notification_text = 'Админ не одобрил Вашу предложенную книгу ' . $book_name . '.';

        $data_user_notifications = array(
            'notification_type' => 'Отказ от предложенной книги',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('d.m.Y'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_suggestion() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_book_suggestions = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->books_model->updateBookSuggestionById($id, $data_book_suggestions);
    }

}