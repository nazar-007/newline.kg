<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getAlbums($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('albums');
        return $query->result();
    }
    public function insertAlbum($data) {
        $this->db->insert('albums', $data);
    }
    public function deleteAlbum($id) {
        $this->db->where('id', $id);
        $this->db->delete('albums');
    }
    public function updateAlbum($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('albums', $data);
    }
}