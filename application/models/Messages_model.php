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
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('guest_messages');
        return $query->result();
    }
    public function getFeedbackMessages() {
        $query = $this->db->get('feedback_messages');
        return $query->result();
    }

    public function insertGuestMessage($data) {
        $this->db->insert('guest_messages', $data);
    }
    public function insertFeedbackMessage($data) {
        $this->db->insert('feedback_messages', $data);
    }

    public function deleteGuestMessage($id) {
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