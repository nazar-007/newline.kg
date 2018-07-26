<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id'),
            'user_email' => $this->session->userdata('user_email'),
        );
        $this->session->set_userdata($sessions);
    }

    public function getAlbumsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('albums');
        return $query->result();
    }
    public function getTotalAlbumsByUserId($user_id) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('user_id');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('albums');
        $albums = $query->result();
        foreach ($albums as $album) {
            $total = $album->total;
            return $total;
        }
    }

    public function insertAlbum($data) {
        $this->db->insert('albums', $data);
    }

    public function deleteAlbumById($id) {
        $this->db->where('id', $id);
        $this->db->delete('albums');
    }
    public function deleteAlbumsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('albums');
    }

    public function updateAlbumById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('albums', $data);
    }
}