<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }

    public function insertAdmin($data) {
        $this->db->insert('admins', $data);
    }
    public function deleteAdminById($id) {
        $this->db->where('id', $id);
        $this->db->delete('admins');
    }
    public function updateAdminById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('admins', $data);
    }

    public function getAdminActions() {
        $query = $this->db->get('admin_actions');
        return $query->result();
    }
    public function insertAdminAction($data) {
        $this->db->insert('admin_actions', $data);
    }
    public function deleteAdminActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('admin_actions');
    }
}