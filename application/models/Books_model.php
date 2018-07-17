<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getBookActionsByFriendIds($friend_ids) {
        $this->db->select('book_actions.*, users.nickname, books.book_name, books.book_image');
        $this->db->from('book_actions');
        $this->db->join('books', 'book_actions.book_id = books.id');
        $this->db->join('users', 'book_actions.action_user_id = users.id');
        $this->db->order_by('book_actions.book_time_unix DESC');
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
    public function getBooksByCategoryIds($category_ids, $offset) {
        $limit = 12;
        $this->db->limit($limit, $offset);
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('books.category_id', $category_id);
            } else {
                $this->db->or_where('books.category_id', $category_id);
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
    public function getBookCategories() {
        $query = $this->db->get('book_categories');
        return $query->result();
    }
    public function getBookCommentNumRowsByIdAndCommentedUserId($id, $commented_user_id) {
        $this->db->where('id', $id);
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('book_comments');
        return $query->num_rows();
    }
    public function getBookCommentsByBookId($book_id) {
        $this->db->select('book_comments.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('book_comments');
        $this->db->join('users', 'book_comments.commented_user_id = users.id');
        $this->db->order_by('comment_date DESC, comment_time DESC');
        $this->db->where('book_comments.book_id', $book_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getBookCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('book_comments');
        return $query->result();
    }
    public function getBookComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('book_complaints');
        return $query->result();
    }
    public function getBookComplaintNumRowsByBookIdAndComplainedUserId($book_id, $complained_user_id) {
        $this->db->where('book_id', $book_id);
        $this->db->where('complained_user_id', $complained_user_id);
        $query = $this->db->get('book_complaints');
        return $query->num_rows();
    }
    public function getBookEmotionNumRowsByBookIdAndEmotionedUserId($book_id, $emotioned_user_id) {
        $this->db->where('book_id', $book_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $query = $this->db->get('book_emotions');
        return $query->num_rows();
    }
    public function getBookFanNumRowsByBookIdAndFanUserId($book_id, $fan_user_id) {
        $this->db->where('book_id', $book_id);
        $this->db->where('fan_user_id', $fan_user_id);
        $query = $this->db->get('book_fans');
        return $query->num_rows();
    }

    public function getBookEmotionsByBookId($book_id) {
        $this->db->select('book_emotions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('book_emotions');
        $this->db->join('users', 'book_emotions.emotioned_user_id = users.id');
        $this->db->order_by('emotion_date DESC, emotion_time DESC');
        $this->db->where('book_emotions.book_id', $book_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getBookFansByBookId($book_id) {
        $this->db->select('book_fans.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('book_fans');
        $this->db->join('users', 'book_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('book_fans.book_id', $book_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getBookFansByFanUserId($fan_user_id) {
        $this->db->select('book_fans.*, books.book_name, books.book_image, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('book_fans');
        $this->db->join('books', 'book_fans.book_id = books.id');
        $this->db->join('users', 'book_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('book_fans.fan_user_id', $fan_user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getBookImageById($id) {
        $this->db->select('id, book_image');
        $this->db->where('id', $id);
        $query = $this->db->get('books');
        $books = $query->result();
        foreach ($books as $book) {
            $book_image = $book->book_image;
        }
        return $book_image;
    }
    public function getBookNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('books');
        return $query->num_rows();
    }
    public function getOneBookById($id) {
        $this->db->select('books.*, book_categories.category_name');
        $this->db->from('books');
        $this->db->join('book_categories', 'books.category_id = book_categories.id');
        $this->db->where('books.id', $id);
        $query = $this->db->get();
        return $query->result();
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
        $book_suggestions = $query->result();
        foreach ($book_suggestions as $book_suggestion) {
            $book_suggestion_file = $book_suggestion->suggestion_file;
        }
        return $book_suggestion_file;
    }
    public function getBookNameById($id) {
        $this->db->select('id, book_name');
        $this->db->where('id', $id);
        $query = $this->db->get('books');
        $books = $query->result();
        foreach ($books as $book) {
            $book_name = $book->book_name;
        }
        return $book_name;
    }
    public function getBookSuggestionImageById($id) {
        $this->db->select('id, suggestion_image');
        $this->db->where('id', $id);
        $query = $this->db->get('book_suggestions');
        $book_suggestions = $query->result();
        foreach ($book_suggestions as $book_suggestion) {
            $book_suggestion_image = $book_suggestion->suggestion_image;
        }
        return $book_suggestion_image;
    }
    public function getBookSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->select('id, suggestion_file, suggestion_image');
        $this->db->where('suggested_user_id', $suggested_user_id);
        $query = $this->db->get('book_suggestions');
        return $query->result();
    }

    public function getTotalByBookIdAndBookTable($book_id, $book_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('book_id');
        $this->db->where('book_id', $book_id);
        $query = $this->db->get($book_table);
        $books = $query->result();
        foreach ($books as $book) {
            $total = $book->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total == 6)) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total == 9)) {
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

    public function insertBook($data) {
        $this->db->insert('books', $data);
    }
    public function insertBookAction($data) {
        $this->db->insert('book_actions', $data);
    }
    public function insertBookCategory($data) {
        $this->db->insert('book_categories', $data);
    }
    public function insertBookComment($data) {
        $this->db->insert('book_comments', $data);
    }
    public function insertBookComplaint($data) {
        $this->db->insert('book_complaints', $data);
    }
    public function insertBookEmotion($data) {
        $this->db->insert('book_emotions', $data);
    }
    public function insertBookFan($data) {
        $this->db->insert('book_fans', $data);
    }
    public function insertBookSuggestion($data) {
        $this->db->insert('book_suggestions', $data);
    }

    public function deleteBookById($id) {
        $this->db->where('id', $id);
        $this->db->delete('books');
    }
    public function deleteBookActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_actions');
    }
    public function deleteBookActionsByActionUserId($action_user_id) {
        $this->db->where('action_user_id', $action_user_id);
        $this->db->delete('book_actions');
    }
    public function deleteBookActionsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_actions');
    }
    public function deleteBookCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_categories');
    }
    public function deleteBookCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_comments');
    }
    public function deleteBookCommentsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_comments');
    }
    public function deleteBookCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $this->db->delete('book_comments');
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
    public function deleteBookEmotionByBookIdAndEmotionedUserId($book_id, $emotioned_user_id) {
        $this->db->where('book_id', $book_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('book_emotions');
    }
    public function deleteBookEmotionsByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_emotions');
    }
    public function deleteBookEmotionsByEmotionedUserId($emotioned_user_id) {
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('book_emotions');
    }
    public function deleteBookFanByBookIdAndFanUserId($book_id, $fan_user_id) {
        $this->db->where('book_id', $book_id);
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('book_fans');
    }
    public function deleteBookFansByBookId($book_id) {
        $this->db->where('book_id', $book_id);
        $this->db->delete('book_fans');
    }
    public function deleteBookFansByFanUserId($fan_user_id) {
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('book_fans');
    }
    public function deleteBookSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('book_suggestions');
    }
    public function deleteBookSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->where('suggested_user_id', $suggested_user_id);
        $this->db->delete('book_suggestions');
    }

    public function updateBookById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('books', $data);
    }
    public function updateBookCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_categories', $data);
    }
    public function updateBookCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_comments', $data);
    }
    public function updateBookComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_complaints', $data);
    }
    public function updateBookEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_emotions', $data);
    }
    public function updateBookSuggestionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('book_suggestions', $data);
    }
}