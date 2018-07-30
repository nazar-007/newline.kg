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
    public function getAlbumNameById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('albums');
        $albums = $query->result();
        foreach ($albums as $album) {
            $album_name = $album->album_name;
        }
        return $album_name;
    }
    public function getAlbumNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('albums');
        return $query->num_rows();
    }
    public function getPublicationAlbumIdByUserId($user_id) {
        $this->db->where('album_name', "Publication Album");
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('albums');
        $albums = $query->result();
        foreach ($albums as $album) {
            $publication_album_id = $album->id;
        }
        return $publication_album_id;
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
    public function getUserIdByAlbumId($album_id) {
        $this->db->where('id', $album_id);
        $query = $this->db->get('albums');
        $albums = $query->result();
        foreach ($albums as $album) {
            $user_id = $album->user_id;
            return $user_id;
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