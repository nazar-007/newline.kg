<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getAdminActions() {
        $query = $this->db->get('admin_actions');
        return $query->result();
    }
    public function getAdminIdByEmailAndPassword($email, $password) {
        $this->db->select('id, email, password');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get("admins");
        $admins = $query->result();
        foreach ($admins as $admin) {
            $admin_id = $admin->id;
        }
        return $admin_id;
    }
    public function getNumRowsByEmail($email) {
        $this->db->select('email');
        $this->db->where('email', $email);
        $query = $this->db->get('admins');
        return $query->num_rows();
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
    public function insertAdminAction($data) {
        $this->db->insert('admin_actions', $data);
    }

    public function deleteAdminActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('admin_actions');
    }
    public function deleteAdminById($id) {
        $this->db->where('id', $id);
        $this->db->delete('admins');
    }
    public function updateAdminById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('admins', $data);
    }
}