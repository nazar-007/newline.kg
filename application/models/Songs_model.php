<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getSongs($category_ids) {
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('category_id', $category_id);
            } else {
                $this->db->or_where('category_id', $category_id);
            }
        }
        $query = $this->db->get('songs');
        return $query->result();
    }
    public function insertSong($data) {
        $this->db->insert('songs', $data);
    }
    public function deleteSong($id) {
        $this->db->where('id', $id);
        $this->db->delete('songs');
    }
    public function updateSong($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('songs', $data);
    }

    public function getSongCategories() {
        $query = $this->db->get('song_categories');
        return $query->result();
    }
    public function insertSongCategory($data) {
        $this->db->insert('song_categories', $data);
    }
    public function deleteSongCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_categories');
    }
    public function updateSongCategory($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_categories', $data);
    }

    public function getSongComments($song_id) {
        $this->db->where('song_id', $song_id);
        $query = $this->db->get('song_comments');
        return $query->result();
    }
    public function insertSongComment($data) {
        $this->db->insert('song_comments', $data);
    }
    public function deleteSongComment($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_comments');
    }
    public function updateSongComment($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_comments', $data);
    }

    public function insertSongComplaint($data) {
        $this->db->insert('song_complaints', $data);
    }
    public function deleteSongComplaint($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_complaints');
    }

    public function insertSongEmotion($data) {
        $this->db->insert('song_emotions', $data);
    }
    public function deleteSongEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_emotions');
    }
    public function updateSongEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_emotions', $data);
    }

    public function insertSongFan($data) {
        $this->db->insert('song_fans', $data);
    }
    public function deleteSongFan($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_fans');
    }

    public function insertSongNotification($data) {
        $this->db->insert('song_notifications', $data);
    }
    public function deleteSongNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_notifications');
    }

    public function getSongSuggestions($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('song_suggestions');
        return $query->result();
    }
    public function insertSongSuggestion($data) {
        $this->db->insert('song_suggestions', $data);
    }
    public function deleteSongSuggestion($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_suggestions');
    }
}