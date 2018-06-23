<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'book_complaints' => $this->books_model->getBookComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_complaints', $data);
    }

    public function insert_book_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $book_id = $this->input->post('book_id');
        $complained_id = $this->input->post('complained_id');

        $data_book_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'book_id' => $book_id,
            'complained_id' => $complained_id
        );

        $this->books_model->insertBookComplaint($data_book_complaints);
    }


}