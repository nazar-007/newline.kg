<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('documents_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = 1;
        $data = array(
            'documents' => $this->documents_model->getDocumentsByUserId($user_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('documents', $data);
    }

    public function insert_document() {
        $document_name = $this->input->post('document_name');
        $document_file = $this->input->post('document_file');
        $document_date = date("d.m.Y");
        $document_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $folder_id = $this->input->post('folder_id');

        $data_documents = array(
            'document_name' => $document_name,
            'document_file' => $document_file,
            'document_date' => $document_date,
            'document_time' => $document_time,
            'user_id' => $user_id,
            'folder_id' => $folder_id
        );
        $this->documents_model->insertDocument($data_documents);
    }

    public function delete_document() {
        $id = $this->input->post('id');
        $this->documents_model->deleteDocumentById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}