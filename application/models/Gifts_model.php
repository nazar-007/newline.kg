<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gifts_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
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
        $query = $this->db->get('gift_categories');
        return $query->result();
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