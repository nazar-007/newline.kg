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
    public function getPublications($friend_ids) {
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
    public function deletePublication($id) {
        $this->db->where('id', $id);
        $this->db->delete('publications');
    }
    public function deleteAllPublicationsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('publications');
    }
    public function updatePublication($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publications', $data);
    }

    public function getPublicationComments($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get('publication_comments');
        return $query->result();
    }
    public function insertPublicationComment($data) {
        $this->db->insert('publication_comments', $data);
    }
    public function deletePublicationComment($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_comments');
    }
    public function updatePublicationComment($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_comments', $data);
    }

    public function insertPublicationComplaint($data) {
        $this->db->insert('publication_complaints', $data);
    }
    public function deletePublicationComplaint($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_complaints');
    }

    public function insertPublicationEmotion($data) {
        $this->db->insert('publication_emotions', $data);
    }
    public function deletePublicationEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_emotions');
    }
    public function updatePublicationEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_emotions', $data);
    }

    public function insertPublicationImage($data) {
        $this->db->insert('publication_images', $data);
    }
    public function deletePublicationImage($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_images');
    }

    public function insertPublicationImageEmotion($data) {
        $this->db->insert('publication_image_emotions', $data);
    }
    public function deletePublicationImageEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_image_emotions');
    }
    public function updatePublicationImageEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_image_emotions', $data);
    }

    public function insertPublicationShare($data) {
        $this->db->insert('publication_shares', $data);
    }
    public function deletePublicationShare($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_shares');
    }

    public function insertPublicationShareEmotion($data) {
        $this->db->insert('publication_share_emotions', $data);
    }
    public function deletePublicationShareEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_share_emotions');
    }
    public function deletePublicationShareEmotionsByPublicationShareId($publication_share_id) {
        $this->db->where('publication_share_id', $publication_share_id);
        $this->db->delete('publication_share_emotions');
    }
    public function updatePublicationShareEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_share_emotions', $data);
    }
}