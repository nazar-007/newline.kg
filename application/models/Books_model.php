<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getBooks($category_ids) {
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
    public function deleteBook($id) {
        $this->db->where('id', $id);
        $this->db->delete('books');
    }
    public function updateBook($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('books', $data);
    }

    public function getBookCategories() {
        $query = $this->db->get('book_categories');
        return $query->result();
    }
    public function insertBookCategory($data) {
        $this->db->insert('book_categories', $data);
    }
    public function deleteBookCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_categories');
    }
    public function updateBookCategory($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_categories', $data);
    }

    public function getBookComments($book_id) {
        $this->db->where('book_id', $book_id);
        $query = $this->db->get('book_comments');
        return $query->result();
    }
    public function insertBookComment($data) {
        $this->db->insert('book_comments', $data);
    }
    public function deleteBookComment($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_comments');
    }
    public function updateBookComment($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comments', $data);
    }

    public function insertBookComplaint($data) {
        $this->db->insert('book_complaints', $data);
    }
    public function deleteBookComplaint($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_complaints');
    }

    public function insertBookEmotion($data) {
        $this->db->insert('book_emotions', $data);
    }
    public function deleteBookEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_emotions');
    }
    public function updateBookEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_emotions', $data);
    }

    public function insertBookFan($data) {
        $this->db->insert('book_fans', $data);
    }
    public function deleteBookFan($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_fans');
    }

    public function insertBookNotification($data) {
        $this->db->insert('book_notifications', $data);
    }
    public function deleteBookNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_notifications');
    }

    public function insertBookSuggestion($data) {
        $this->db->insert('book_suggestions', $data);
    }
    public function deleteBookSuggestion($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_suggestions');
    }
}