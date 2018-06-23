<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getEventsByCategoryIds($category_ids) {
        foreach ($category_ids as $key => $category_id) {
            if ($key == 0) {
                $this->db->where('category_id', $category_id);
            } else {
                $this->db->or_where('category_id', $category_id);
            }
        }
        $query = $this->db->get('events');
        return $query->result();
    }

    public function insertEvent($data) {
        $this->db->insert('events', $data);
    }
    public function deleteEventById($id) {
        $this->db->where('id', $id);
        $this->db->delete('events');
    }
    public function updateEventById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('events', $data);
    }

    public function getEventActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('user_id', $friend_id);
            } else {
                $this->db->or_where('user_id', $friend_id);
            }
        }
        $query = $this->db->get('event_actions');
        return $query->result();
    }
    public function insertEventAction($data) {
        $this->db->insert('event_actions', $data);
    }
    public function deleteEventActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_actions');
    }

    public function getEventCategories() {
        $query = $this->db->get('event_categories');
        return $query->result();
    }
    public function insertEventCategory($data) {
        $this->db->insert('event_categories', $data);
    }
    public function deleteEventCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_categories');
    }
    public function updateEventCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_categories', $data);
    }

    public function getEventCommentsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $query = $this->db->get('event_comments');
        return $query->result();
    }
    public function insertEventComment($data) {
        $this->db->insert('event_comments', $data);
    }
    public function deleteEventCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_comments');
    }
    public function updateEventCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_comments', $data);
    }

    public function getEventComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('event_complaints');
        return $query->result();
    }
    public function insertEventComplaint($data) {
        $this->db->insert('event_complaints', $data);
    }
    public function deleteEventComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_complaints');
    }

    public function insertEventEmotion($data) {
        $this->db->insert('event_emotions', $data);
    }
    public function deleteEventEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_emotions');
    }
    public function updateEventEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_emotions', $data);
    }

    public function getEventFansByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $query = $this->db->get('event_fans');
        return $query->result();
    }
    public function insertEventFan($data) {
        $this->db->insert('event_fans', $data);
    }
    public function deleteEventFanById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_fans');
    }

    public function getEventSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('event_suggestions');
        return $query->result();
    }
    public function insertEventSuggestion($data) {
        $this->db->insert('event_suggestions', $data);
    }
    public function deleteEventSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_suggestions');
    }
}