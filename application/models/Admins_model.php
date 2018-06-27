<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getRandomAdminIdByAdminTable($admin_table) {
        $this->db->select('id, admin_table');
        $this->db->where('admin_table', $admin_table);
        $this->db->order_by('rand()');
        $this->db->limit(1);
        $query = $this->db->get('admins');
        $admins = $query->result();
        foreach ($admins as $admin) {
            $admin_id = $admin->id;
        }
        return $admin_id;
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