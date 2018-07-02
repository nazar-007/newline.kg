<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_comment_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'book_comment_complaints' => $this->books_model->getBookCommentComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_comment_complaints', $data);
    }

    public function insert_book_comment_complaint() {

        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('books');
        $book_comment_id = $this->input->post('book_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_book_comment_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'book_comment_id' => $book_comment_id,
            'commented_user_id' => $commented_user_id,
            'complained_user_id' => $complained_user_id
        );

        $this->books_model->insertBookCommentComplaint($data_book_comment_complaints);
    }

    public function delete_book_comment_complaint() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookCommentComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_book_comment_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->books_model->deleteBookCommentComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_comment_complaint() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_book_comment_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->books_model->updateBookCommentComplaintById($id, $data_book_comment_complaints);
    }
}