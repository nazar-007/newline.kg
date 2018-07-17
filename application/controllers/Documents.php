<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('documents_model');
        $this->load->model('folders_model');
        $this->load->model('users_model');
    }

    public function Index($folder_id) {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $folder_num_rows = $this->folders_model->getFolderNumRowsById($folder_id);
        if ($folder_num_rows > 0) {
            $user_id = $this->folders_model->getUserIdByFolderId($folder_id);
        } else {
            $user_id = 0;
        }
        if ($user_id == $session_user_id && $folder_num_rows > 0) {
            $data_documents = array(
                'documents' => $this->documents_model->getDocumentsByFolderId($folder_id),
                'documents_error' => '',
                'folder_id' => $folder_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_documents = array(
                'documents_error' => 'Это не Ваша папка! Кыш отсюда!',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('documents', $data_documents);
    }

    public function One_document($folder_id, $document_id) {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $folder_num_rows = $this->folders_model->getFolderNumRowsById($folder_id);
        $document_num_rows = $this->documents_model->getDocumentNumRowsByIdAndFolderId($document_id, $folder_id);

        if ($folder_num_rows > 0 && $document_num_rows > 0) {
            $user_id = $this->documents_model->getUserIdByFolderIdAndDocumentId($folder_id, $document_id);
        } else {
            $user_id = 0;
        }
        if ($user_id == $session_user_id && $folder_num_rows > 0 && $document_num_rows > 0) {
            $data_documents = array(
                'one_document' => $this->documents_model->getOneDocumentById($document_id),
                'documents_error' => '',
                'folder_id' => $folder_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_documents = array(
                'documents_error' => 'Это не Ваш документ! Кыш отсюда!',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('one_document', $data_documents);
    }

    public function insert_document() {
        $document_name = $this->input->post('document_name');
        $document_description = '';
        $document_date = date("d.m.Y");
        $document_time = date("H:i:s");
        $user_id = $_SESSION['user_id'];
        $folder_id = $this->input->post('folder_id');

        $folder_num_rows = $this->folders_model->getFolderNumRowsByIdAndUserId($folder_id, $user_id);

        if ($document_name != '' && $folder_num_rows > 0) {
            $data_documents = array(
                'document_name' => $document_name,
                'document_description' => $document_description,
                'document_date' => $document_date,
                'document_time' => $document_time,
                'user_id' => $user_id,
                'folder_id' => $folder_id
            );
            $this->documents_model->insertDocument($data_documents);
            $insert_id = $this->db->insert_id();

            $insert_json = array(
                'id' => $insert_id,
                'document_error' => '',
                'document_name' => $document_name,
                'document_date' => $document_date,
                'document_time' => $document_time,
                'folder_id' => $folder_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'document_error' => 'Невозможно создать документ: название файла пусто или не найдена папка.',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_document() {
        $id = $this->input->post('id');
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $document_num_rows = $this->documents_model->getDocumentNumRowsByIdAndUserId($id, $session_user_id);
        if ($document_num_rows > 0) {
            $this->documents_model->deleteDocumentById($id);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'document_error' => "Вы не сможете удалить документ, потому что это не Ваш документ!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function update_document() {
        $id = $this->input->post('id');
        $session_user_id = $_SESSION['user_id'];
        $document_name = $this->input->post('document_name');
        $document_description = $this->input->post('document_description');
        $document_date = date("d.m.Y");
        $document_time = date("H:i:s");

        $document_num_rows = $this->documents_model->getDocumentNumRowsByIdAndUserId($id, $session_user_id);
        if ($document_num_rows > 0 && $document_name != '') {
            $data_documents = array(
                'document_name' => $document_name,
                'document_description' => $document_description,
                'document_date' => $document_date,
                'document_time' => $document_time,
            );
            $this->documents_model->updateDocumentById($id, $data_documents);

            $update_json = array(
                'document_success' => 'Ваш документ успешно изменён',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $update_json = array(
                'document_error' => 'Не удалось сохранить изменения. Введите название документа',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($update_json);
    }
}