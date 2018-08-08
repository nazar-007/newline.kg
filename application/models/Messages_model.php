<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {
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
    public function getGuestMessagesByUserId($user_id) {
        $this->db->select('guest_messages.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('guest_messages');
        $this->db->join('users', 'guest_messages.guest_id = users.id');
        $this->db->order_by('message_date DESC, message_time DESC');
        $this->db->where('guest_messages.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getGuestMessageNumRowsByIdAndGuestId($id, $guest_id) {
        $this->db->where('id', $id);
        $this->db->where('guest_id', $guest_id);
        $query = $this->db->get('guest_messages');
        return $query->num_rows();
    }
    public function getFeedbackMessagesByAdminId($admin_id) {
        $this->db->select('feedback_messages.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('feedback_messages');
        $this->db->join('users', 'feedback_messages.user_id = users.id');
        $this->db->where('admin_id', $admin_id);
        $this->db->order_by('id DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insertGuestMessage($data) {
        $this->db->insert('guest_messages', $data);
    }
    public function insertFeedbackMessage($data) {
        $this->db->insert('feedback_messages', $data);
    }

    public function deleteGuestMessageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('guest_messages');
    }
    public function deleteGuestMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('guest_messages');
    }
    public function deleteGuestMessagesByUserIdOrGuestId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('guest_id', $user_id);
        $this->db->delete('guest_messages');
    }
    public function deleteFeedbackMessageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('feedback_messages');
    }
    public function deleteFeedbackMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('feedback_messages');
    }
    public function deleteAllFeedbackMessages() {
        $this->db->delete('feedback_messages');
    }

    public function updateGuestMessageById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('guest_messages', $data);

    }
}