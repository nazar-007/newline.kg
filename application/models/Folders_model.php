<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getFolders($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folders');
        return $query->result();
    }
    public function insertFolder($data) {
        $this->db->insert('folders', $data);
    }
    public function deleteFolder($id) {
        $this->db->where('id', $id);
        $this->db->delete('folders');
    }
    public function updateFolder($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('folders', $data);
    }
}