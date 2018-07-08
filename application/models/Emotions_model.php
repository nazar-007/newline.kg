<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emotions_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getEmotions() {
        $query = $this->db->get('emotions');
        return $query->result();
    }

    public function insertEmotion($data) {
        $this->db->insert('emotions', $data);
    }

    public function deleteEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('emotions');
    }

    public function updateEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('emotions', $data);
    }
}