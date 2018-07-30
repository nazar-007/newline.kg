<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id'),
            'user_email' => $this->session->userdata('user_email')
        );
        $this->session->set_userdata($sessions);
    }

    public function getOneSongById($id) {
        $this->db->select('songs.*, song_categories.category_name');
        $this->db->from('songs');
        $this->db->join('song_categories', 'songs.category_id = song_categories.id');
        $this->db->where('songs.id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongs() {
        $this->db->order_by('id DESC');
        $query = $this->db->get('songs');
        return $query->result();
    }
    public function getSongActionsByFriendIds($friend_ids) {
        $this->db->select('song_actions.*, users.nickname, songs.song_name, songs.song_file');
        $this->db->from('song_actions');
        $this->db->join('songs', 'song_actions.song_id = songs.id');
        $this->db->join('users', 'song_actions.action_user_id = users.id');
        $this->db->order_by('song_actions.song_time_unix DESC');
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
            }
        }
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongsByCategoryIds($category_ids, $offset) {
        $this->db->order_by('id DESC');
        $limit = 12;
        $this->db->limit($limit, $offset);
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
    public function getSongCategories() {
        $query = $this->db->get('song_categories');
        return $query->result();
    }
    public function getSongCommentNumRowsByIdAndCommentedUserId($id, $commented_user_id) {
        $this->db->where('id', $id);
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('song_comments');
        return $query->num_rows();
    }
    public function getSongCommentsBySongId($song_id) {
        $this->db->select('song_comments.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('song_comments');
        $this->db->join('users', 'song_comments.commented_user_id = users.id');
        $this->db->order_by('comment_date DESC, comment_time DESC');
        $this->db->where('song_comments.song_id', $song_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('song_comments');
        return $query->result();
    }
    public function getSongComplaintsByAdminId($admin_id) {
        $this->db->select('song_complaints.*, users.email, users.nickname, users.surname, users.main_image, songs.song_name');
        $this->db->from('song_complaints');
        $this->db->join('users', 'song_complaints.complained_user_id = users.id');
        $this->db->join('songs', 'song_complaints.song_id = songs.id');
        $this->db->where('admin_id', $admin_id);
        $this->db->order_by('id DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongComplaintNumRowsBySongIdAndComplainedUserId($song_id, $complained_user_id) {
        $this->db->where('song_id', $song_id);
        $this->db->where('complained_user_id', $complained_user_id);
        $query = $this->db->get('song_complaints');
        return $query->num_rows();
    }
    public function getSongEmotionNumRowsBySongIdAndEmotionedUserId($song_id, $emotioned_user_id) {
        $this->db->where('song_id', $song_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $query = $this->db->get('song_emotions');
        return $query->num_rows();
    }
    public function getSongEmotionsBySongId($song_id) {
        $this->db->select('song_emotions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('song_emotions');
        $this->db->join('users', 'song_emotions.emotioned_user_id = users.id');
        $this->db->order_by('emotion_date DESC, emotion_time DESC');
        $this->db->where('song_emotions.song_id', $song_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongFanNumRowsBySongIdAndFanUserId($song_id, $fan_user_id) {
        $this->db->where('song_id', $song_id);
        $this->db->where('fan_user_id', $fan_user_id);
        $query = $this->db->get('song_fans');
        return $query->num_rows();
    }
    public function getSongFansBySongId($song_id) {
        $this->db->select('song_fans.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('song_fans');
        $this->db->join('users', 'song_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('song_fans.song_id', $song_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongFansByFanUserId($fan_user_id) {
        $this->db->select('song_fans.*, songs.song_name, songs.song_file, songs.song_singer, users.nickname, users.surname, users.main_image');
        $this->db->from('song_fans');
        $this->db->join('songs', 'song_fans.song_id = songs.id');
        $this->db->join('users', 'song_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('song_fans.fan_user_id', $fan_user_id);
        $query = $this->db->get();
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
    public function getSongNameById($id) {
        $this->db->select('id, song_name');
        $this->db->where('id', $id);
        $query = $this->db->get('songs');
        $songs = $query->result();
        foreach ($songs as $song) {
            $song_name = $song->song_name;
        }
        return $song_name;
    }
    public function getSongNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('songs');
        return $query->num_rows();
    }
    public function getSongSuggestionsByAdminId($admin_id) {
        $this->db->select('song_suggestions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('song_suggestions');
        $this->db->join('users', 'song_suggestions.suggested_user_id = users.id');
        $this->db->where('admin_id', $admin_id);
        $this->db->order_by('id DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function getSongSuggestionFileById($id) {
        $this->db->select('id, suggestion_file');
        $this->db->where('id', $id);
        $query = $this->db->get('song_suggestions');
        $song_suggestions = $query->result();
        foreach ($song_suggestions as $song_suggestion) {
            $song_suggestion_file = $song_suggestion->suggestion_file;
        }
        return $song_suggestion_file;
    }
    public function getSongSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->select('id, suggestion_file, suggestion_image');
        $this->db->where('suggested_user_id', $suggested_user_id);
        $query = $this->db->get('song_suggestions');
        return $query->result();
    }
    public function getTotalBySongIdAndSongTable($song_id, $song_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('song_id');
        $this->db->where('song_id', $song_id);
        $query = $this->db->get($song_table);
        $songs = $query->result();
        foreach ($songs as $song) {
            $total = $song->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total) == 6) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total) == 9) {
                return substr($total, 0, 3) . "M";
            } else if (strlen($total) == 10) {
                return substr($total, 0, 1) . "B";
            } else if(strlen($total) == 11) {
                return substr($total, 0, 2) . "B";
            } else if(strlen($total) == 12) {
                return substr($total, 0, 3) . "B";
            } else {
                return $total;
            }
        }
    }
    public function getTotalCommonSongsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT COUNT(*) as total
            FROM song_fans 
            WHERE song_id IN 
            (SELECT song_id 
            FROM song_fans 
            WHERE fan_user_id 
            IN ($user1,$user2) 
            GROUP BY song_id 
            HAVING COUNT(song_id) = 2) 
            AND fan_user_id = $user1"
        );
        $songs = $query->result();
        foreach ($songs as $song) {
            $total = $song->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total) == 6) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total) == 9) {
                return substr($total, 0, 3) . "M";
            } else if (strlen($total) == 10) {
                return substr($total, 0, 1) . "B";
            } else if(strlen($total) == 11) {
                return substr($total, 0, 2) . "B";
            } else if(strlen($total) == 12) {
                return substr($total, 0, 3) . "B";
            } else {
                return $total;
            }
        }
    }
    public function getCommonSongsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT song_fans.*, songs.song_name, songs.song_singer, songs.song_file
                FROM song_fans
                INNER JOIN songs ON(song_fans.song_id = songs.id)
                WHERE song_id IN
                (SELECT song_id
                FROM song_fans
                WHERE fan_user_id IN ($user1,$user2)
                GROUP BY song_id
                HAVING COUNT(song_id) = 2)
                AND song_fans.fan_user_id = $user1"
        );
        return $query->result();
    }
    public function searchSongsBySongName($song_name) {
        $this->db->like('song_name', $song_name);
        $this->db->or_like('song_singer', $song_name);
        $query = $this->db->get('songs');
        return $query->result();
    }

    public function insertSong($data) {
        $this->db->insert('songs', $data);
    }
    public function insertSongAction($data) {
        $this->db->insert('song_actions', $data);
    }
    public function insertSongCategory($data) {
        $this->db->insert('song_categories', $data);
    }
    public function insertSongComment($data) {
        $this->db->insert('song_comments', $data);
    }
    public function insertSongComplaint($data) {
        $this->db->insert('song_complaints', $data);
    }
    public function insertSongEmotion($data) {
        $this->db->insert('song_emotions', $data);
    }
    public function insertSongFan($data) {
        $this->db->insert('song_fans', $data);
    }
    public function insertSongSuggestion($data) {
        $this->db->insert('song_suggestions', $data);
    }

    public function deleteSongById($id) {
        $this->db->where('id', $id);
        $this->db->delete('songs');
    }
    public function deleteSongActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_actions');
    }
    public function deleteSongActionsByActionUserId($action_user_id) {
        $this->db->where('action_user_id', $action_user_id);
        $this->db->delete('song_actions');
    }
    public function deleteSongActionsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_actions');
    }
    public function deleteSongCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_categories');
    }
    public function deleteSongCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_comments');
    }
    public function deleteSongCommentsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_comments');
    }
    public function deleteSongCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $this->db->delete('song_comments');
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
    public function deleteSongEmotionBySongIdAndEmotionedUserId($song_id, $emotioned_user_id) {
        $this->db->where('song_id', $song_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('song_emotions');
    }
    public function deleteSongEmotionsBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_emotions');
    }
    public function deleteSongEmotionsByEmotionedUserId($emotioned_user_id) {
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('song_emotions');
    }
    public function deleteSongFanBySongIdAndFanUserId($song_id, $fan_user_id) {
        $this->db->where('song_id', $song_id);
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('song_fans');
    }
    public function deleteSongFansBySongId($song_id) {
        $this->db->where('song_id', $song_id);
        $this->db->delete('song_fans');
    }
    public function deleteSongFansByFanUserId($fan_user_id) {
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('song_fans');
    }
    public function deleteSongSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('song_suggestions');
    }
    public function deleteSongSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->where('suggested_user_id', $suggested_user_id);
        $this->db->delete('song_suggestions');
    }

    public function updateSongById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('songs', $data);
    }
    public function updateSongCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_categories', $data);
    }
    public function updateSongCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_comments', $data);
    }
    public function updateSongComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_complaints', $data);
    }
    public function updateSongEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_emotions', $data);
    }
    public function updateSongSuggestionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('song_suggestions', $data);
    }
}