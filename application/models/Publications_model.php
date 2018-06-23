<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications_model extends CI_Model {

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
    public function getPublicationsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('user_id', $friend_id);
            } else {
                $this->db->or_where('user_id', $friend_id);
            }
        }
        $query = $this->db->get('publications');
        return $query->result();
    }

    public function insertPublication($data) {
        $this->db->insert('publications', $data);
    }
    public function deletePublicationById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publications');
    }
    public function deleteAllPublicationsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('publications');
    }
    public function updatePublicationById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publications', $data);
    }

    public function getPublicationCommentsByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get('publication_comments');
        return $query->result();
    }
    public function insertPublicationComment($data) {
        $this->db->insert('publication_comments', $data);
    }
    public function deletePublicationCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_comments');
    }
    public function updatePublicationCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_comments', $data);
    }

    public function getPublicationComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('publication_complaints');
        return $query->result();
    }
    public function insertPublicationComplaint($data) {
        $this->db->insert('publication_complaints', $data);
    }
    public function deletePublicationComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_complaints');
    }

    public function insertPublicationEmotion($data) {
        $this->db->insert('publication_emotions', $data);
    }
    public function deletePublicationEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_emotions');
    }
    public function updatePublicationEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_emotions', $data);
    }

    public function getPublicationImagesByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get('publication_images');
        return $query->result();
    }
    public function insertPublicationImage($data) {
        $this->db->insert('publication_images', $data);
    }
    public function deletePublicationImageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_images');
    }

    public function insertPublicationImageEmotion($data) {
        $this->db->insert('publication_image_emotions', $data);
    }
    public function deletePublicationImageEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_image_emotions');
    }
    public function updatePublicationImageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_image_emotions', $data);
    }

    public function getPublicationSharesByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get('publication_shares');
        return $query->result();
    }
    public function insertPublicationShare($data) {
        $this->db->insert('publication_shares', $data);
    }
    public function deletePublicationShareById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_shares');
    }

    public function insertPublicationShareEmotion($data) {
        $this->db->insert('publication_share_emotions', $data);
    }
    public function deletePublicationShareEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_share_emotions');
    }
    public function deletePublicationShareEmotionsByPublicationShareId($publication_share_id) {
        $this->db->where('publication_share_id', $publication_share_id);
        $this->db->delete('publication_share_emotions');
    }
    public function updatePublicationShareEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_share_emotions', $data);
    }
}