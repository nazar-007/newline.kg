<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications_model extends CI_Model {
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
    public function getOnePublicationById($id) {
        $this->db->select('publications.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publications');
        $this->db->join('users', 'publications.published_user_id = users.id');
        $this->db->where('publications.id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublications() {
        $this->db->order_by('id DESC');
        $query = $this->db->get('publications');
        return $query->result();
    }
    public function getPublicationsByPublishedUserId($published_user_id) {
        $this->db->where('published_user_id', $published_user_id);
        $this->db->order_by('publication_date DESC, publication_time DESC');
        $query = $this->db->get('publications');
        return $query->result();
    }
    public function getPublicationsByFriendIds($friend_ids, $offset) {
        $this->db->select('publications.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publications');
        $this->db->join('users', 'publications.published_user_id = users.id');
        $this->db->order_by('publication_date DESC, publication_time DESC');
        $limit = 2;
        $this->db->limit($limit, $offset);
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('publications.published_user_id', $friend_id);
            } else {
                $this->db->or_where('publications.published_user_id', $friend_id);
            }
        }
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationCommentsByPublicationId($publication_id) {
        $this->db->select('publication_comments.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publication_comments');
        $this->db->join('users', 'publication_comments.commented_user_id = users.id');
        $this->db->order_by('comment_date DESC, comment_time DESC');
        $this->db->where('publication_comments.publication_id', $publication_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationCommentNumRowsByIdAndCommentedUserId($id, $commented_user_id) {
        $this->db->select('id, commented_user_id');
        $this->db->where('id', $id);
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('publication_comments');
        return $query->num_rows();
    }
    public function getPublicationComplaintsByAdminId($admin_id) {
        $this->db->select('publication_complaints.*, users.email, users.nickname, users.surname, users.main_image, publications.publication_name, publications.publication_description');
        $this->db->from('publication_complaints');
        $this->db->join('users', 'publication_complaints.complained_user_id = users.id');
        $this->db->join('publications', 'publication_complaints.publication_id = publications.id');
        $this->db->where('admin_id', $admin_id);
        $this->db->order_by('id DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $complained_user_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->where('complained_user_id', $complained_user_id);
        $query = $this->db->get('publication_complaints');
        return $query->num_rows();
    }
    public function getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $emotioned_user_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $query = $this->db->get('publication_emotions');
        return $query->num_rows();
    }
    public function getPublicationEmotionsByPublicationId($publication_id) {
        $this->db->select('publication_emotions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publication_emotions');
        $this->db->join('users', 'publication_emotions.emotioned_user_id = users.id');
        $this->db->order_by('emotion_date DESC, emotion_time DESC');
        $this->db->where('publication_emotions.publication_id', $publication_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationIdByPublicationImageId($publication_image_id) {
        $this->db->where('id', $publication_image_id);
        $query = $this->db->get('publication_images');
        $publication_images = $query->result();
        foreach ($publication_images as $publication_image) {
            $publication_id = $publication_image->publication_id;
        }
        return $publication_id;
    }
    public function getPublishedUserIdByPublicationId($publication_id) {
        $this->db->where('id', $publication_id);
        $query = $this->db->get('publications');
        $publications = $query->result();
        foreach ($publications as $publication) {
            $published_user_id = $publication->published_user_id;
        }
        return $published_user_id;
    }
    public function getPublicationImagesByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get('publication_images');
        return $query->result();
    }
    public function getPublicationImagesByAlbumId($album_id) {
        $this->db->where('album_id', $album_id);
        $query = $this->db->get('publication_images');
        return $query->result();
    }
    public function getPublicationImageEmotionsByPublicationImageId($publication_image_id) {
        $this->db->select('publication_image_emotions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publication_image_emotions');
        $this->db->join('users', 'publication_image_emotions.emotioned_user_id = users.id');
        $this->db->order_by('publication_image_emotions.emotion_date DESC, publication_image_emotions.emotion_time DESC');
        $this->db->where('publication_image_emotions.publication_image_id', $publication_image_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationImageFileById($id) {
        $this->db->select('id, publication_image_file');
        $this->db->where('id', $id);
        $query = $this->db->get('publication_images');
        $publication_images = $query->result();
        foreach ($publication_images as $publication_image) {
            $publication_image_file = $publication_image->publication_image_file;
        }
        return $publication_image_file;
    }
    public function getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $emotioned_user_id) {
        $this->db->where('publication_image_id', $publication_image_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $query = $this->db->get('publication_image_emotions');
        return $query->num_rows();
    }
    public function getPublicationNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('publications');
        return $query->num_rows();
    }
    public function getPublicationNumRowsByIdAndPublishedUserId($id, $published_user_id) {
        $this->db->where('id', $id);
        $this->db->where('published_user_id', $published_user_id);
        $query = $this->db->get('publications');
        return $query->num_rows();
    }
    public function getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $shared_user_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->where('shared_user_id', $shared_user_id);
        $query = $this->db->get('publication_shares');
        return $query->num_rows();
    }
    public function getPublicationSharesByPublicationId($publication_id) {
        $this->db->select('publication_shares.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('publication_shares');
        $this->db->join('users', 'publication_shares.shared_user_id = users.id');
        $this->db->order_by('share_date DESC, share_time DESC');
        $this->db->where('publication_shares.publication_id', $publication_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPublicationSharesBySharedUserId($shared_user_id) {
        $this->db->select('publication_shares.*, users.email, users.nickname, users.surname, users.main_image, publications.publication_name, publications.publication_description, publications.publication_date, publications.publication_time, publications.published_user_id');
        $this->db->from('publication_shares');
        $this->db->join('publications', 'publication_shares.publication_id = publications.id');
        $this->db->join('users', 'publication_shares.shared_user_id = users.id');
        $this->db->order_by('share_date DESC, share_time DESC');
        $this->db->where('publication_shares.shared_user_id', $shared_user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getTotalByPublicationIdAndPublicationTable($publication_id, $publication_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('publication_id');
        $this->db->where('publication_id', $publication_id);
        $query = $this->db->get($publication_table);
        $publications = $query->result();
        foreach ($publications as $publication) {
            $total = $publication->total;
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
    public function getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, $publication_image_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('publication_image_id');
        $this->db->where('publication_image_id', $publication_image_id);
        $query = $this->db->get($publication_image_table);
        $publication_images = $query->result();
        foreach ($publication_images as $publication_image) {
            $total = $publication_image->total;
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

    public function insertPublication($data) {
        $this->db->insert('publications', $data);
    }
    public function insertPublicationComment($data) {
        $this->db->insert('publication_comments', $data);
    }
    public function insertPublicationComplaint($data) {
        $this->db->insert('publication_complaints', $data);
    }
    public function insertPublicationEmotion($data) {
        $this->db->insert('publication_emotions', $data);
    }
    public function insertPublicationImage($data) {
        $this->db->insert('publication_images', $data);
    }
    public function insertPublicationImageEmotion($data) {
        $this->db->insert('publication_image_emotions', $data);
    }
    public function insertPublicationShare($data) {
        $this->db->insert('publication_shares', $data);
    }
    public function insertPublicationShareEmotion($data) {
        $this->db->insert('publication_share_emotions', $data);
    }

    public function deletePublicationById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publications');
    }
    public function deletePublicationsByPublishedUserId($published_user_id) {
        $this->db->where('published_user_id', $published_user_id);
        $this->db->delete('publications');
    }
    public function deletePublicationCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_comments');
    }
    public function deletePublicationCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $this->db->delete('publication_comments');
    }
    public function deletePublicationCommentsByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->delete('publication_comments');
    }
    public function deletePublicationComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_complaints');
    }
    public function deletePublicationComplaintsByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->delete('publication_complaints');
    }
    public function deletePublicationComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('publication_complaints');
    }
    public function deletePublicationComplaintsByPublishedUserIdOrComplainedUserId($user_id) {
        $this->db->where('published_user_id', $user_id);
        $this->db->or_where('complained_user_id', $user_id);
        $this->db->delete('publication_complaints');
    }
    public function deletePublicationEmotionByPublicationIdAndEmotionedUserId($publication_id, $emotioned_user_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('publication_emotions');
    }
    public function deletePublicationEmotionsByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->delete('publication_emotions');
    }
    public function deletePublicationEmotionsByPublishedUserIdOrEmotionedUserId($user_id) {
        $this->db->where('published_user_id', $user_id);
        $this->db->or_where('emotioned_user_id', $user_id);
        $this->db->delete('publication_emotions');
    }
    public function deletePublicationImageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_images');
    }
    public function deletePublicationImagesByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->delete('publication_images');
    }
    public function deletePublicationImageEmotionByPublicationImageIdAndEmotionedUserId($publication_image_id, $emotioned_user_id) {
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->where('publication_image_id', $publication_image_id);
        $this->db->delete('publication_image_emotions');
    }
    public function deletePublicationImageEmotionsByPublicationImageId($publication_image_id) {
        $this->db->where('publication_image_id', $publication_image_id);
        $this->db->delete('publication_image_emotions');
    }
    public function deletePublicationImageEmotionsByEmotionedUserId($user_id) {
        $this->db->where('emotioned_user_id', $user_id);
        $this->db->delete('publication_image_emotions');
    }
    public function deletePublicationShareByPublicationIdAndSharedUserId($publication_id, $shared_user_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->where('shared_user_id', $shared_user_id);
        $this->db->delete('publication_shares');
    }
    public function deletePublicationSharesByPublicationId($publication_id) {
        $this->db->where('publication_id', $publication_id);
        $this->db->delete('publication_shares');
    }
    public function deletePublicationSharesBySharedUserId($shared_user_id) {
        $this->db->where('shared_user_id', $shared_user_id);
        $this->db->delete('publication_shares');
    }
    public function deletePublicationShareEmotionsBySharedUserIdOrEmotionedUserId($user_id) {
        $this->db->where('shared_user_id', $user_id);
        $this->db->or_where('emotioned_user_id', $user_id);
        $this->db->delete('publication_shares');
    }
    public function deletePublicationShareEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('publication_share_emotions');
    }
    public function deletePublicationShareEmotionsByPublicationShareId($publication_share_id) {
        $this->db->where('publication_share_id', $publication_share_id);
        $this->db->delete('publication_share_emotions');
    }

    public function updatePublicationById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publications', $data);
    }
    public function updatePublicationCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_comments', $data);
    }
    public function updatePublicationComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_complaints', $data);
    }
    public function updatePublicationEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_emotions', $data);
    }
    public function updatePublicationImageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_image_emotions', $data);
    }
    public function updatePublicationShareEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('publication_share_emotions', $data);
    }
}