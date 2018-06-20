<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }

//    public function getAllBooks() {
//        $query = $this->db->get('books');
//        return $query->result();
//    }

    public function insertAdmin($data) {
        $this->db->insert('admins', $data);
    }
    public function deleteAdmin($id) {
        $this->db->where('id', $id);
        $this->db->delete('admins');
    }
    public function updateAdmin($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('admins', $data);
    }

    public function insertAdminNotification($data) {
        $this->db->insert('admin_notifications', $data);
    }
    public function deleteAdminNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('admin_notifications');
    }
}