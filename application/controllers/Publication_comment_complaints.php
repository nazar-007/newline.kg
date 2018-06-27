<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_comment_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'publication_comment_complaints' => $this->publications_model->getPublicationCommentComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_comment_complaints', $data);
    }

    public function insert_publication_comment_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $published_user_id = $this->input->post('published_user_id');
        $publication_id = $this->input->post('publication_id');
        $publication_comment_id = $this->input->post('publication_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_publication_comment_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'published_user_id' => $published_user_id,
            'publication_id' => $publication_id,
            'publication_comment_id' => $publication_comment_id,
            'commented_user_id' => $commented_user_id,
            'complained_user_id' => $complained_user_id
        );
        $this->publications_model->insertPublicationCommentComplaint($data_publication_comment_complaints);
    }

    public function delete_publication_comment_complaint() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationCommentComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_publication_comment_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->publications_model->deletePublicationCommentComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}