<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getEventActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
            }
        }
        $query = $this->db->get('event_actions');
        return $query->result();
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
    public function getEventCategories() {
        $query = $this->db->get('event_categories');
        return $query->result();
    }
    public function getEventCommentsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $query = $this->db->get('event_comments');
        return $query->result();
    }
    public function getEventCommentsByCommentedUserId($commented_user_id) {
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('event_comments');
        return $query->result();
    }
    public function getEventComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('event_complaints');
        return $query->result();
    }
    public function getEventFansByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $query = $this->db->get('event_fans');
        return $query->result();
    }
    public function getEventFansByFanUserId($fan_user_id) {
        $this->db->select('event_fans.*, events.event_name, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('event_fans');
        $this->db->join('events', 'event_fans.event_id = events.id');
        $this->db->join('users', 'event_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('event_fans.fan_user_id', $fan_user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getEventNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('events');
        return $query->num_rows();
    }
    public function getOneEventById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('events');
        return $query->result();
    }
    public function getEventSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('event_suggestions');
        return $query->result();
    }

    public function insertEvent($data) {
        $this->db->insert('events', $data);
    }
    public function insertEventAction($data) {
        $this->db->insert('event_actions', $data);
    }
    public function insertEventCategory($data) {
        $this->db->insert('event_categories', $data);
    }
    public function insertEventComment($data) {
        $this->db->insert('event_comments', $data);
    }
    public function insertEventComplaint($data) {
        $this->db->insert('event_complaints', $data);
    }
    public function insertEventEmotion($data) {
        $this->db->insert('event_emotions', $data);
    }
    public function insertEventFan($data) {
        $this->db->insert('event_fans', $data);
    }
    public function insertEventSuggestion($data) {
        $this->db->insert('event_suggestions', $data);
    }

    public function deleteEventById($id) {
        $this->db->where('id', $id);
        $this->db->delete('events');
    }
    public function deleteEventActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_actions');
    }
    public function deleteEventActionsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->delete('event_actions');
    }
    public function deleteEventActionsByActionUserId($action_user_id) {
        $this->db->where('action_user_id', $action_user_id);
        $this->db->delete('event_actions');
    }
    public function deleteEventCategoryById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_categories');
    }
    public function deleteEventCommentById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_comments');
    }
    public function deleteEventCommentsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->delete('event_comments');
    }
    public function deleteEventCommentsByCommentedUserId($event_id) {
        $this->db->where('commented_user_id', $event_id);
        $this->db->delete('event_comments');
    }
    public function deleteEventComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_complaints');
    }
    public function deleteEventComplaintsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->delete('event_complaints');
    }
    public function deleteEventComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('event_complaints');
    }
    public function deleteEventEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_emotions');
    }
    public function deleteEventEmotionsByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->delete('event_emotions');
    }
    public function deleteEventEmotionsByEmotionedUserId($emotioned_user_id) {
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $this->db->delete('event_emotions');
    }
    public function deleteEventFanById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_fans');
    }
    public function deleteEventFansByEventId($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->delete('event_fans');
    }
    public function deleteEventFansByFanUserId($fan_user_id) {
        $this->db->where('fan_user_id', $fan_user_id);
        $this->db->delete('event_fans');
    }
    public function deleteEventSuggestionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_suggestions');
    }
    public function deleteEventSuggestionsBySuggestedUserId($suggested_user_id) {
        $this->db->where('suggested_user_id', $suggested_user_id);
        $this->db->delete('event_suggestions');
    }

    public function updateEventById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('events', $data);
    }
    public function updateEventCategoryById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_categories', $data);
    }
    public function updateEventCommentById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_comments', $data);
    }
    public function updateEventComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_complaints', $data);
    }
    public function updateEventEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_emotions', $data);
    }
    public function updateEventSuggestionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_suggestions', $data);
    }
}