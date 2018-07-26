<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('admins_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'book_complaints' => $this->books_model->getBookComplaintsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_complaints', $data);
    }

    public function insert_book_complaint() {
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('books');
        $book_id = $this->input->post('book_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->books_model->getBookComplaintNumRowsByBookIdAndComplainedUserId($book_id, $complained_user_id);
        if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
            $data_book_complaints = array(
                'complaint_text' => $complaint_text,
                'complaint_time_unix' => $complaint_time_unix,
                'admin_id' => $admin_id,
                'book_id' => $book_id,
                'complained_user_id' => $complained_user_id
            );
            $this->books_model->insertBookComplaint($data_book_complaints);
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                'book_id' => $book_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную книгу, или текст жалобы пуст, или что-то пошло не так.",
                'book_id' => $book_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_book_complaint() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_book_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->books_model->deleteBookComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_complaint() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_book_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->books_model->updateBookComplaintById($id, $data_book_complaints);
    }
}