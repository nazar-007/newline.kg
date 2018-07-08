<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stakes_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getStakesByCategoryIds($category_ids) {
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('category_id', $category_id);
            } else {
                $this->db->or_where('category_id', $category_id);
            }
        }
        $query = $this->db->get('stakes');
        return $query->result();
    }
    public function getStakeCategories() {
        $query = $this->db->get('stake_categories');
        return $query->result();
    }
    public function getStakeFansByStakeId($stake_id) {
        $this->db->where('stake_id', $stake_id);
        $query = $this->db->get('stake_fans');
        return $query->result();
    }

    public function insertStake($data) {
        $this->db->insert('stakes', $data);
    }
    public function insertStakeCategory($data) {
        $this->db->insert('stake_categories', $data);
    }
    public function insertStakeFan($data) {
        $this->db->insert('stake_fans', $data);
    }

    public function deleteStakeById($id) {
        $this->db->where('id', $id);
        $this->db->delete('stakes');
    }
    public function deleteStakeCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('stake_categories');
    }
    public function deleteStakeFanById($id) {
        $this->db->where('id', $id);
        $this->db->delete('stake_fans');
    }
    public function deleteStakeFansByFanUserId($fan_user_id) {
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('stake_fans');
    }

    public function updateStakeById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stakes', $data);
    }
    public function updateStakeCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stake_categories', $data);
    }
    public function updateStakeFanById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stake_fans', $data);
    }
}