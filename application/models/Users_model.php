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
    public function deleteUser($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }
    public function updateUser($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    public function insertFriend($data) {
        $this->db->insert('friends', $data);
    }
    public function deleteFriend($id) {
        $this->db->where('id', $id);
        $this->db->delete('friends');
    }

    public function insertGuest($data) {
        $this->db->insert('guests', $data);
    }
    public function deleteGuest($id) {
        $this->db->where('id', $id);
        $this->db->delete('guests');
    }
    public function updateGuest($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('guests', $data);
    }

    public function insertUserComplaint($data) {
        $this->db->insert('user_complaints', $data);
    }
    public function deleteUserComplaint($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_complaints');
    }

    public function insertUserImage($data) {
        $this->db->insert('user_images', $data);
    }
    public function deleteUserImage($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_images');
    }

    public function insertUserImageComment($data) {
        $this->db->insert('user_image_comments', $data);
    }
    public function deleteUserImageComment($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_comments');
    }
    public function updateUserImageComment($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_comments', $data);
    }

    public function insertUserImageEmotion($data) {
        $this->db->insert('user_image_emotions', $data);
    }
    public function deleteUserImageEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_emotions');
    }
    public function updateUserImageEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_emotions', $data);
    }

    public function insertUserImageNotification($data) {
        $this->db->insert('user_image_notifications', $data);
    }
    public function deleteUserImageNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_notifications');
    }

    public function insertUserNotification($data) {
        $this->db->insert('user_notifications', $data);
    }
    public function deleteUserNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_notifications');
    }

    public function insertUserPageEmotion($data) {
        $this->db->insert('user_page_emotions', $data);
    }
    public function deleteUserPageEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_page_emotions');
    }
    public function updateUserPageEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_page_emotions', $data);
    }
}