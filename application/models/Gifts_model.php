<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gifts_model extends CI_Model {
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

    public function getGiftsByCategoryIds($category_ids) {
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('category_id', $category_id);
            } else {
                $this->db->or_where('category_id', $category_id);
            }
        }
        $query = $this->db->get('gifts');
        return $query->result();
    }
    public function getGiftCategories() {
        $this->db->order_by('category_name ASC');
        $query = $this->db->get('gift_categories');
        return $query->result();
    }
    public function getGiftPriceById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('gifts');
        $gifts = $query->result();
        foreach ($gifts as $gift) {
            $gift_price = $gift->gift_price;
        }
        return $gift_price;
    }
    public function getGiftSentByUserId($user_id) {
        $this->db->select('gift_sent.*, gifts.gift_name, gifts.gift_file, users.email, users.nickname, users.surname');
        $this->db->from('gift_sent');
        $this->db->join('gifts', 'gift_sent.gift_id = gifts.id');
        $this->db->join('users', 'gift_sent.sent_user_id = users.id');
        $this->db->order_by('gift_sent.sent_date DESC, gift_sent.sent_time DESC');
        $this->db->where('gift_sent.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getGiftSentNumRowsByIdAndUserId($id, $user_id) {
        $this->db->select('id, user_id');
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('gift_sent');
        return $query->num_rows();
    }

    public function insertGift($data) {
        $this->db->insert('gifts', $data);
    }
    public function insertGiftCategory($data) {
        $this->db->insert('gift_categories', $data);
    }
    public function insertGiftSent($data) {
        $this->db->insert('gift_sent', $data);
    }

    public function deleteGiftById($id) {
        $this->db->where('id', $id);
        $this->db->delete('gifts');
    }
    public function deleteGiftCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('gift_categories');
    }
    public function deleteGiftSentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('gift_sent');
    }
    public function deleteGiftSentByUserIdOrSentUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('sent_user_id', $user_id);
        $this->db->delete('gift_sent');
    }

    public function updateGiftById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('gifts', $data);
    }
    public function updateGiftCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('gift_categories', $data);
    }
}