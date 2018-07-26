<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'admin_id' => $this->session->userdata('admin_id'),
            'admin_email' => $this->session->userdata('admin_email'),
            'admin_table' => $this->session->userdata('admin_table')
        );
        $this->session->set_userdata($sessions);
    }

    public function getAdminTableByAdminEmail($admin_email) {
        $this->db->where('admin_email', $admin_email);
        $query = $this->db->get("admins");
        $admins = $query->result();
        foreach ($admins as $admin) {
            $admin_table = $admin->admin_table;
        }
        return $admin_table;
    }

    public function getAdminActions() {
        $query = $this->db->get('admin_actions');
        return $query->result();
    }
    public function getAdminIdByAdminEmailAndAdminPassword($admin_email, $admin_password) {
        $this->db->select('id, admin_email, admin_password');
        $this->db->where('admin_email', $admin_email);
        $this->db->where('admin_password', $admin_password);
        $query = $this->db->get("admins");
        $admins = $query->result();
        foreach ($admins as $admin) {
            $admin_id = $admin->id;
        }
        return $admin_id;
    }
    public function getNumRowsByAdminEmailAndAdminPassword($admin_email, $admin_password) {
        $this->db->select('id, admin_email, admin_password');
        $this->db->where('admin_email', $admin_email);
        $this->db->where('admin_password', $admin_password);
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
    public function getRandomSuperAdminId() {
        $this->db->select('id');
        $this->db->where('admin_type', 'super_admin');
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