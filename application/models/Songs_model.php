<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getSongsByCategoryIds($category_ids) {
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
    public function getSongFileById($id) {
        $this->db->select('id, song_file');
        $this->db->where('id', $id);
        $query = $this->db->get('songs');
        $songs = $query->result();
        foreach ($songs as $song) {
            $song_file = $song->song_file;
        }
        return $song_file;
    }
    public function insertSong($data) {
        $this->db->insert('songs', $data);
    }
    public function deleteSongById($id) {
        $this->db->where('id', $id);
        $this->db->delete('songs');
    }
    public function updateSongById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('songs', $data);
    }

    public function getSongActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
            }
        }
        $query = $this->db->get('song_actions');
        return $query->result();
    }
    public function insertSongAction($data) {
        $this->db->insert('song_actions', $data);
    }
    public function deleteSongActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_actions');
    }
    public function deleteSongActionsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_actions');
    }

    public function getSongCategories() {
        $query = $this->db->get('song_categories');
        return $query->result();
    }
    public function insertSongCategory($data) {
        $this->db->insert('song_categories', $data);
    }
    public function deleteSongCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_categories');
    }
    public function updateSongCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_categories', $data);
    }

    public function getSongCommentsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $query = $this->db->get('song_comments');
        return $query->result();
    }
    public function insertSongComment($data) {
        $this->db->insert('song_comments', $data);
    }
    public function deleteSongCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_comments');
    }
    public function deleteSongCommentsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_comments');
    }
    public function updateSongCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_comments', $data);
    }

    public function getSongCommentComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('song_comment_complaints');
        return $query->result();
    }
    public function insertSongCommentComplaint($data) {
        $this->db->insert('song_comment_complaints', $data);
    }
    public function deleteSongCommentComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_comment_complaints');
    }
    public function deleteSongCommentComplaintsBySongCommentId($song_comment_id) {
        $this->db->where('song_comment_id', $song_comment_id);
        $this->db->delete('song_comment_complaints');
    }
    public function deleteSongCommentComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complaint_user_id', $complained_user_id);
        $this->db->delete('song_comment_complaints');
    }
    public function updateSongCommentComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_comment_complaints', $data);
    }

    public function insertSongCommentEmotion($data) {
        $this->db->insert('song_comment_emotions', $data);
    }
    public function deleteSongCommentEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_comment_emotions');
    }
    public function deleteSongCommentEmotionsBySongCommentId($song_comment_id) {
        $this->db->where('song_comment_id', $song_comment_id);
        $this->db->delete('song_comment_emotions');
    }
    public function updateSongCommentEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_comment_emotions', $data);
    }

    public function getSongComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('song_complaints');
        return $query->result();
    }
    public function insertSongComplaint($data) {
        $this->db->insert('song_complaints', $data);
    }
    public function deleteSongComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_complaints');
    }
    public function deleteSongComplaintsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_complaints');
    }
    public function deleteSongComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('song_complaints');
    }
    public function updateSongComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_complaints', $data);
    }

    public function insertSongEmotion($data) {
        $this->db->insert('song_emotions', $data);
    }
    public function deleteSongEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_emotions');
    }
    public function deleteSongEmotionsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_emotions');
    }
    public function updateSongEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_emotions', $data);
    }

    public function getSongFansBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $query = $this->db->get('song_fans');
        return $query->result();
    }
    public function insertSongFan($data) {
        $this->db->insert('song_fans', $data);
    }
    public function deleteSongFanById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_fans');
    }
    public function deleteSongFansBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_fans');
    }
    public function getSongSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('song_suggestions');
        return $query->result();
    }
    public function getSongSuggestionFileById($id) {
        $this->db->select('id, suggestion_file');
        $this->db->where('id', $id);
        $query = $this->db->get('song_suggestions');
        $songs = $query->result();
        foreach ($songs as $song) {
            $song_suggestion_file = $song->suggestion_file;
        }
        return $song_suggestion_file;
    }
    public function getSongSuggestionImageById($id) {
        $this->db->select('id, suggestion_image');
        $this->db->where('id', $id);
        $query = $this->db->get('song_suggestions');
        $songs = $query->result();
        foreach ($songs as $song) {
            $song_suggestion_image = $song->suggestion_image;
        }
        return $song_suggestion_image;
    }
    public function getSongSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->select('id, suggestion_file, suggestion_image');
        $this->db->where('suggested_user_id', $suggested_user_id);
        $query = $this->db->get('song_suggestions');
        return $query->result();
    }

    public function insertSongSuggestion($data) {
        $this->db->insert('song_suggestions', $data);
    }
    public function deleteSongSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_suggestions');
    }
    public function deleteSongSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->where('suggested_user_id', $suggested_user_id);
        $this->db->delete('song_suggestions');
    }
    public function updateSongSuggestionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_suggestions', $data);
    }
}