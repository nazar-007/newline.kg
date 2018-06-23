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
        $user_id = 1;
        $data = array(
            'folders' => $this->folders_model->getFoldersByUserId($user_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('folders', $data);
    }

    public function insert_folder() {
        $folder_name = $this->input->post('folder_name');
        $user_id = $this->input->post('user_id');

        $data_folders = array(
            'folder_name' => $folder_name,
            'user_id' => $user_id
        );

        if ($folder_name != 'User Folder') {
            $this->folders_model->insertFolder($data_folders);
        }
    }

    public function delete_folder() {
        $id = $this->input->post('id');
        $folder_name = $this->input->post('folder_name');

        if ($folder_name != 'My folder') {
            $this->folders_model->deleteFolderById($id);
            $this->documents_model->deleteDocumentsByFolderId($id);
        }

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}