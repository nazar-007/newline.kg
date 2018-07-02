<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'publication_complaints' => $this->publications_model->getPublicationComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_complaints', $data);
    }

    public function insert_publication_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('publications');
        $published_user_id = $this->input->post('published_user_id');
        $publication_id = $this->input->post('publication_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_publication_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'published_user_id' => $published_user_id,
            'publication_id' => $publication_id,
            'complained_user_id' => $complained_user_id
        );
        $this->publications_model->insertPublicationComplaint($data_publication_complaints);
    }

    public function delete_publication_complaint() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_publication_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->publications_model->deletePublicationComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_publication_complaint() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_publication_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->publications_model->updatePublicationComplaintById($id, $data_publication_complaints);
    }
}