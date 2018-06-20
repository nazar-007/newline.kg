<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getPrivateMessages($user_id) {
        $this->db->where('from_id', $user_id);
        $query = $this->db->get('private_messages');
        return $query->result();
    }
    public function getGuestMessages($user_id) {
        $this->db->where('to_id', $user_id);
        $query = $this->db->get('guest_messages');
        return $query->result();
    }
    public function getFeedbackMessages() {
        $query = $this->db->get('feedback_messages');
        return $query->result();
    }
    public function insertPrivateMessage($data) {
        $this->db->insert('private_messages', $data);
    }
    public function deletePrivateMessage($id) {
        $this->db->where('id', $id);
        $this->db->delete('private_messages');
    }
    public function deletePrivateMessagesByUserId($user_id) {
        $this->db->where('to_id', $user_id);
        $this->db->delete('private_messages');
    }
    public function insertGuestMessage($data) {
        $this->db->insert('guest_messages', $data);
    }
    public function deleteGuestMessage($id) {
        $this->db->where('id', $id);
        $this->db->delete('guest_messages');
    }
    public function deleteAllGuestMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('guest_messages');
    }
    public function insertFeedbackMessage($data) {
        $this->db->insert('feedback_messages', $data);
    }
    public function deleteFeedbackMessage($id) {
        $this->db->where('id', $id);
        $this->db->delete('feedback_messages');
    }
    public function deleteAllFeedbackMessages() {
        $this->db->delete('feedback_messages');
    }
}