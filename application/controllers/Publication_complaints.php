<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
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
        $admin_id = $this->input->post('admin_id');
        $publication_id = $this->input->post('publication_id');
        $complained_id = $this->input->post('complained_id');

        $data_publication_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'publication_id' => $publication_id,
            'complained_id' => $complained_id
        );
        $this->publications_model->insertPublicationComplaint($data_publication_complaints);
    }
}