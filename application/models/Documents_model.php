<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id'),
            'user_email' => $this->session->userdata('user_email'),
        );
        $this->session->set_userdata($sessions);
    }

    public function getDocumentsByFolderId($folder_id) {
        $this->db->where('folder_id', $folder_id);
        $query = $this->db->get('documents');
        return $query->result();
    }
    public function getDocumentNumRowsByIdAndFolderId($id, $folder_id) {
        $this->db->select('id, folder_id');
        $this->db->where('id', $id);
        $this->db->where('folder_id', $folder_id);
        $query = $this->db->get('documents');
        return $query->num_rows();
    }
    public function getDocumentNumRowsByIdAndUserId($id, $user_id) {
        $this->db->select('id, user_id');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('documents');
        return $query->num_rows();
    }
    public function getOneDocumentById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('documents');
        return $query->result();
    }
    public function getUserIdByFolderIdAndDocumentId($folder_id, $document_id) {
        $this->db->where('id', $document_id);
        $this->db->where('folder_id', $folder_id);
        $query = $this->db->get('documents');
        $documents = $query->result();
        foreach ($documents as $document) {
            $user_id = $document->user_id;
        }
        return $user_id;
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
    public function deleteDocumentsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('documents');
    }

    public function updateDocumentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('documents', $data);
    }
}