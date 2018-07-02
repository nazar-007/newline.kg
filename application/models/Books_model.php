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
    public function getBookFileById($id) {
        $this->db->select('id, book_file');
        $this->db->where('id', $id);
        $query = $this->db->get('books');
        $books = $query->result();
        foreach ($books as $book) {
            $book_file = $book->book_file;
        }
        return $book_file;
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
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
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
    public function deleteBookActionsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
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
    public function deleteBookCommentsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_comments');
    }
    public function updateBookCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comments', $data);
    }

    public function getBookCommentComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('book_comment_complaints');
        return $query->result();
    }
    public function insertBookCommentComplaint($data) {
        $this->db->insert('book_comment_complaints', $data);
    }
    public function deleteBookCommentComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_comment_complaints');
    }
    public function deleteBookCommentComplaintsByBookCommentId($book_comment_id) {
        $this->db->where('book_comment_id', $book_comment_id);
        $this->db->delete('book_comment_complaints');
    }
    public function deleteBookCommentComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complaint_user_id', $complained_user_id);
        $this->db->delete('book_comment_complaints');
    }
    public function updateBookCommentComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comment_complaints', $data);
    }
    public function insertBookCommentEmotion($data) {
        $this->db->insert('book_comment_emotions', $data);
    }
    public function deleteBookCommentEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_comment_emotions');
    }
    public function deleteBookCommentEmotionsByBookCommentId($book_comment_id) {
        $this->db->where('book_comment_id', $book_comment_id);
        $this->db->delete('book_comment_emotions');
    }
    public function updateBookCommentEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comment_emotions', $data);
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
    public function deleteBookComplaintsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_complaints');
    }
    public function deleteBookComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('book_complaints');
    }
    public function updateBookComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_complaints', $data);
    }

    public function insertBookEmotion($data) {
        $this->db->insert('book_emotions', $data);
    }
    public function deleteBookEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_emotions');
    }
    public function deleteBookEmotionsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
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
    public function deleteBookFansByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_fans');
    }

    public function getBookSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('book_suggestions');
        return $query->result();
    }
    public function getBookSuggestionFileById($id) {
        $this->db->select('id, suggestion_file');
        $this->db->where('id', $id);
        $query = $this->db->get('book_suggestions');
        $books = $query->result();
        foreach ($books as $book) {
            $book_suggestion_file = $book->suggestion_file;
        }
        return $book_suggestion_file;
    }
    public function getBookSuggestionImageById($id) {
        $this->db->select('id, suggestion_image');
        $this->db->where('id', $id);
        $query = $this->db->get('book_suggestions');
        $books = $query->result();
        foreach ($books as $book) {
            $book_suggestion_image = $book->suggestion_image;
        }
        return $book_suggestion_image;
    }
    public function getBookSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->select('id, suggestion_file, suggestion_image');
        $this->db->where('suggested_user_id', $suggested_user_id);
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
    public function deleteBookSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->where('suggested_user_id', $suggested_user_id);
        $this->db->delete('book_suggestions');
    }
    public function updateBookSuggestionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_suggestions', $data);
    }
}