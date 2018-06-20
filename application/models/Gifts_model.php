<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gifts_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getGifts($category_ids) {
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
    public function insertGift($data) {
        $this->db->insert('gifts', $data);
    }
    public function deleteGift($id) {
        $this->db->where('id', $id);
        $this->db->delete('gifts');
    }
    public function updateGift($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('gifts', $data);
    }

    public function getGiftCategories() {
        $query = $this->db->get('gift_categories');
        return $query->result();
    }
    public function insertGiftCategory($data) {
        $this->db->insert('gift_categories', $data);
    }
    public function deleteGiftCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('gift_categories');
    }
    public function updateGiftCategory($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('gift_categories', $data);
    }

    public function insertGiftSent($data) {
        $this->db->insert('gift_sent', $data);
    }
    public function deleteGiftSent($id) {
        $this->db->where('id', $id);
        $this->db->delete('gift_sent');
    }
}