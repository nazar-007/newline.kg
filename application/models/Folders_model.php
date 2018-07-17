<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Folders_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id'),
            'user_email' => $this->session->userdata('user_email')
        );
        $this->session->set_userdata($sessions);
    }

    public function getFoldersByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folders');
        return $query->result();
    }
    public function getFolderNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('folders');
        return $query->num_rows();
    }
    public function getFolderNumRowsByUserId($user_id) {
        $this->db->select('id');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folders');
        return $query->num_rows();
    }
    public function getFolderNumRowsByIdAndUserId($id, $user_id) {
        $this->db->select('id, user_id');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('folders');
        return $query->num_rows();
    }
    public function getUserIdByFolderId($folder_id) {
        $this->db->select('id, user_id');
        $this->db->where('id', $folder_id);
        $query = $this->db->get("folders");
        $folders = $query->result();
        foreach ($folders as $folder) {
            $user_id = $folder->user_id;
        }
        return $user_id;
    }

    public function insertFolder($data) {
        $this->db->insert('folders', $data);
    }

    public function deleteFolderById($id) {
        $this->db->where('id', $id);
        $this->db->delete('folders');
    }
    public function deleteFoldersByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('folders');
    }

    public function updateFolderById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('folders', $data);
    }
}