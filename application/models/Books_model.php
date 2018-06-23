<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getBooksByCategoryIds($category_ids) {
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('category_id', $category_id);
            } else {
                $this->db->or_where('category_id', $category_id);
            }
        }
        $query = $this->db->get('books');
        return $query->result();
    }
    public function insertBook($data) {
        $this->db->insert('books', $data);
    }
    public function deleteBookById($id) {
        $this->db->where('id', $id);
        $this->db->delete('books');
    }
    public function updateBookById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('books', $data);
    }

    public function getBookActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('user_id', $friend_id);
            } else {
                $this->db->or_where('user_id', $friend_id);
            }
        }
        $query = $this->db->get('book_actions');
        return $query->result();
    }
    public function insertBookAction($data) {
        $this->db->insert('book_actions', $data);
    }
    public function deleteBookActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_actions');
    }

    public function getBookCategories() {
        $query = $this->db->get('book_categories');
        return $query->result();
    }
    public function insertBookCategory($data) {
        $this->db->insert('book_categories', $data);
    }
    public function deleteBookCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_categories');
    }
    public function updateBookCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_categories', $data);
    }

    public function getBookCommentsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $query = $this->db->get('book_comments');
        return $query->result();
    }
    public function insertBookComment($data) {
        $this->db->insert('book_comments', $data);
    }
    public function deleteBookCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_comments');
    }
    public function updateBookCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comments', $data);
    }

    public function getBookComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('book_complaints');
        return $query->result();
    }
    public function insertBookComplaint($data) {
        $this->db->insert('book_complaints', $data);
    }
    public function deleteBookComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_complaints');
    }

    public function insertBookEmotion($data) {
        $this->db->insert('book_emotions', $data);
    }
    public function deleteBookEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_emotions');
    }
    public function updateBookEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_emotions', $data);
    }

    public function getBookFansByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $query = $this->db->get('book_fans');
        return $query->result();
    }
    public function insertBookFan($data) {
        $this->db->insert('book_fans', $data);
    }
    public function deleteBookFanById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_fans');
    }

    public function getBookSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('book_suggestions');
        return $query->result();
    }
    public function insertBookSuggestion($data) {
        $this->db->insert('book_suggestions', $data);
    }
    public function deleteBookSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_suggestions');
    }
}