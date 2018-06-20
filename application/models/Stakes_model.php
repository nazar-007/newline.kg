<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stakes_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getStakes($category_ids) {
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

    public function insertStake($data) {
        $this->db->insert('stakes', $data);
    }
    public function deleteStake($id) {
        $this->db->where('id', $id);
        $this->db->delete('stakes');
    }
    public function updateStake($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stakes', $data);
    }

    public function getStakeCategories() {
        $query = $this->db->get('stake_categories');
        return $query->result();
    }
    public function insertStakeCategory($data) {
        $this->db->insert('stake_categories', $data);
    }
    public function deleteStakeCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('stake_categories');
    }
    public function updateStakeCategory($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stake_categories', $data);
    }

    public function insertStakeFan($data) {
        $this->db->insert('stake_fans', $data);
    }
    public function deleteStakeFan($id) {
        $this->db->where('id', $id);
        $this->db->delete('stake_fans');
    }
    public function updateStakeFan($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('stake_fans', $data);
    }
}