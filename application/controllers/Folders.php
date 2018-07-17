<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('folders_model');
        $this->load->model('documents_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = $_SESSION['user_id'];
        $data = array(
            'folders' => $this->folders_model->getFoldersByUserId($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('session_user');
        $this->load->view('folders', $data);
    }

    public function insert_folder() {
        $folder_name = $this->input->post('folder_name');
        $user_id = $_SESSION['user_id'];

        if ($folder_name != 'My Folder') {
            $data_folders = array(
                'folder_name' => $folder_name,
                'user_id' => $user_id
            );
            $this->folders_model->insertFolder($data_folders);

            $insert_id = $this->db->insert_id();

            $insert_json = array(
                'id' => $insert_id,
                'folder_name' => $folder_name,
                'csrf_hash' => $this->security->get_csrf_hash(),
                'user_id' => $user_id
            );
        } else {
            $insert_json = array(
                'folder_name' => $folder_name,
                'folder_error' => "Папка My Folder установлена по умолчанию, поэтому Вы не сможете создать папку с таким же названием!",
                'csrf_hash' => $this->security->get_csrf_hash(),
                'user_id' => $user_id
            );
        }

        echo json_encode($insert_json);
    }

    public function delete_folder() {
        $id = $this->input->post('id');
        $session_user_id = $_SESSION['user_id'];
        $folder_num_rows = $this->folders_model->getFolderNumRowsByIdAndUserId($id, $session_user_id);
        $folder_name = $this->input->post('folder_name');
        if ($folder_name != 'My Folder' && $folder_num_rows > 0) {
            $this->folders_model->deleteFolderById($id);
            $this->documents_model->deleteDocumentsByFolderId($id);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else if ($folder_num_rows == 0) {
            $delete_json = array(
                'folder_error' => "Вы не сможете удалить папку, потому что это не Ваша папка!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'folder_name' => $folder_name,
                'folder_error' => "Папка My Folder установлена по умолчанию, поэтому её невозможно удалить!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function update_folder() {
        $id = $this->input->post('id');
        $folder_name = $this->input->post('folder_name');

        if ($folder_name != 'My Folder') {
            $data_folders = array(
                'folder_name' => $folder_name
            );
            $this->folders_model->updateFolderById($id, $data_folders);
            $update_json = array(
                'id' => $id,
                'folder_name' => $folder_name,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $update_json = array(
                'folder_name' => $folder_name,
                'folder_error' => "Папка My Folder установлена по умолчанию, поэтому Вы не сможете так переименовать свою папку!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($update_json);
    }
}