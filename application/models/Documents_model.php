<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getDocumentsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('documents');
        return $query->result();
    }
    public function insertDocument($data) {
        $this->db->insert('documents', $data);
    }
    public function deleteDocumentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('documents');
    }
    public function deleteDocumentsByFolderId($folder_id) {
        $this->db->where('folder_id', $folder_id);
        $this->db->delete('documents');
    }
    public function updateDocumentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('documents', $data);
    }
}