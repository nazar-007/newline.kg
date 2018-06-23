<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
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

    public function insertUserImage($data) {
        $this->db->insert('user_images', $data);
    }
    public function deleteUserImageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_images');
    }

    public function getUserImageActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('user_id', $friend_id);
            } else {
                $this->db->or_where('user_id', $friend_id);
            }
        }
        $query = $this->db->get('user_image_actions');
        return $query->result();
    }
    public function insertUserImageAction($data) {
        $this->db->insert('user_image_actions', $data);
    }
    public function deleteUserImageActionById($id) {
        $this->db->where('id', $id);
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
    public function updateUserImageCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_comments', $data);
    }

    public function insertUserImageEmotion($data) {
        $this->db->insert('user_image_emotions', $data);
    }
    public function deleteUserImageEmotionById($id) {
        $this->db->where('id', $id);
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