<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getFoldersByUserId($user_id) {
        $this->db->where('folder_id', $user_id);
        $query = $this->db->get('folders');
    }
    public function insertFolder($data) {
        $this->db->insert('folders', $data);
    }
    public function deleteFolderById($id) {
        $this->db->where('id', $id);
        $this->db->delete('folders');
    }
    public function updateFolderById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('folders', $data);
    }
}