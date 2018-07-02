<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $this->session->set_userdata($sessions);
    }
    public function getUserIdByEmailAndPassword($email, $password) {
        $this->db->select('id, email, password');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $user_id = $user->id;
        }
        return $user_id;
    }
    public function getPasswordById($id) {
        $this->db->select('id, password');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $password = $user->password;
        }
        return $password;
    }

    public function getEducationSchoolsById($id) {
        $this->db->select('id, education_schools');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $education_schools = $user->education_schools;
        }
        return $education_schools;
    }

    public function getHomeLandById($id) {
        $this->db->select('id, home_land');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $home_land = $user->home_land;
        }
        return $home_land;
    }

    public function getFamilyPositionById($id) {
        $this->db->select('id, family_position');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $family_position = $user->family_position;
        }
        return $family_position;
    }

    public function getEducationUniversitiesById($id) {
        $this->db->select('id, education_universities');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $education_universities = $user->education_universities;
        }
        return $education_universities;
    }

    public function getCurrencyById($id) {
        $this->db->select('id, currency');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $currency = $user->currency;
        }
        return $currency;
    }
    public function getRatingById($id) {
        $this->db->select('id, rating');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $rating = $user->rating;
        }
        return $rating;
    }
    public function getRankById($id) {
        $this->db->select('id, rank');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $rank = $user->rank;
        }
        return $rank;
    }
    public function getNumRowsByEmail($email) {
        $this->db->select('email');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows();
    }
    public function getNumRowsByEmailAndPassword($email, $password) {
        $this->db->select('id, email, password');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        return $query->num_rows();
    }
    public function updateRankById($id, $rating, $rank) {
        if ($rating < 0) {
            $data = array(
                'rank' => 'Лузер'
            );
        } else if ($rating >= 0 && $rating < 50) {
            $data = array(
                'rank' => 'Новичок'
            );
        } else if ($rating >= 50 && $rating < 100) {
            $data = array(
                'rank' => "Умник"
            );
        } else if ($rating >= 100 && $rating < 150) {
            $data = array(
                'rank' => "Гений"
            );
        } else if ($rating >= 150 && $rating < 200) {
            $data = array(
                'rank' => "Мудрец"
            );
        } else if ($rating >= 200) {
            $data = array(
                'rank' => "Высший разум"
            );
        } else {
            $data = array(
                'rank' => $rank
            );
        }
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    public function insertUser($data) {
        $this->db->insert('users', $data);
    }
    public function deleteUserById($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }
    public function updateUserById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    public function getUserBlacklistByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_blacklist');
        return $query->result();
    }
    public function insertUserBlacklist($data) {
        $this->db->insert('user_blacklist', $data);
    }
    public function deleteUserBlacklistById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_blacklist');
    }

    public function getFriendsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('friends');
        return $query->result();
    }
    public function insertFriend($data) {
        $this->db->insert('friends', $data);
    }
    public function deleteFriendByUserIdAndFriendId($user_id, $friend_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('friend_id', $friend_id);
        $this->db->delete('friends');
    }

    public function getGuestsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('guests');
        return $query->result();
    }
    public function insertGuest($data) {
        $this->db->insert('guests', $data);
    }
    public function deleteGuestById($id) {
        $this->db->where('id', $id);
        $this->db->delete('guests');
    }
    public function updateGuestById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('guests', $data);
    }

    public function getUserComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('user_complaints');
        return $query->result();
    }
    public function insertUserComplaint($data) {
        $this->db->insert('user_complaints', $data);
    }
    public function deleteUserComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_complaints');
    }
    public function deleteUserComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('user_complaints');
    }
    public function updateUserComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_complaints', $data);
    }

    public function getUserImagesByAlbumId($album_id) {
        $this->db->where('album_id', $album_id);
        $query = $this->db->get('user_images');
        return $query->result();
    }
    public function insertUserImage($data) {
        $this->db->insert('user_images', $data);
    }
    public function deleteUserImageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_images');
    }
    public function deleteUserImagesByAlbumId($album_id) {
        $this->db->where('album_id', $album_id);
        $this->db->delete('user_images');
    }

    public function getUserImageActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
            }
        }
        $query = $this->db->get('user_image_actions');
        return $query->result();
    }
    public function getUserImageActionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $query = $this->db->get('user_actions');
        return $query->result();
    }
    public function insertUserImageAction($data) {
        $this->db->insert('user_image_actions', $data);
    }
    public function deleteUserImageActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_actions');
    }
    public function deleteUserImageActionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_actions');
    }

    public function getUserImageCommentsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $query = $this->db->get('user_image_comments');
        return $query->result();
    }

    public function insertUserImageComment($data) {
        $this->db->insert('user_image_comments', $data);
    }
    public function deleteUserImageCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_comments');
    }
    public function deleteUserImageCommentsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_comments');
    }
    public function updateUserImageCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_comments', $data);
    }

    public function insertUserImageCommentEmotion($data) {
        $this->db->insert('user_image_comment_emotions', $data);
    }
    public function deleteUserImageCommentEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_comment_emotions');
    }
    public function deleteUserImageCommentEmotionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_comment_emotions');
    }
    public function deleteUserImageCommentEmotionsByUserImageCommentId($user_image_comment_id) {
        $this->db->where('user_image_comment_id', $user_image_comment_id);
        $this->db->delete('user_image_comment_emotions');
    }
    public function updateUserImageCommentEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_comment_emotions', $data);
    }

    public function insertUserImageEmotion($data) {
        $this->db->insert('user_image_emotions', $data);
    }
    public function deleteUserImageEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_emotions');
    }
    public function deleteUserImageEmotionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_emotions');
    }
    public function updateUserImageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_emotions', $data);
    }

    public function insertUserInvite($data) {
        $this->db->insert('user_invites', $data);
    }
    public function deleteUserInviteByUserIdAndInvitedId($user_id, $invited_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('invited_id', $invited_id);
        $this->db->delete('user_invites');
    }
    public function insertUserNotification($data) {
        $this->db->insert('user_notifications', $data);
    }
    public function deleteUserNotificationById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_notifications');
    }
    public function deleteUserNotificationsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_notifications');
    }

    public function insertUserPageEmotion($data) {
        $this->db->insert('user_page_emotions', $data);
    }
    public function deleteUserPageEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_page_emotions');
    }
    public function updateUserPageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_page_emotions', $data);
    }
}