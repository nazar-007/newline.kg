<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    public function getEvents($category_ids) {
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
    public function deleteEvent($id) {
        $this->db->where('id', $id);
        $this->db->delete('events');
    }
    public function updateEvent($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('events', $data);
    }

    public function getEventCategories() {
        $query = $this->db->get('event_categories');
        return $query->result();
    }
    public function insertEventCategory($data) {
        $this->db->insert('event_categories', $data);
    }
    public function deleteEventCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_categories');
    }
    public function updateEventCategory($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_categories', $data);
    }

    public function getEventComments($book_id) {
        $this->db->where('event_id', $book_id);
        $query = $this->db->get('event_comments');
        return $query->result();
    }
    public function insertEventComment($data) {
        $this->db->insert('event_comments', $data);
    }
    public function deleteEventComment($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_comments');
    }
    public function updateEventComment($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_comments', $data);
    }

    public function insertEventComplaint($data) {
        $this->db->insert('event_complaints', $data);
    }
    public function deleteEventComplaint($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_complaints');
    }

    public function insertEventEmotion($data) {
        $this->db->insert('event_emotions', $data);
    }
    public function deleteEventEmotion($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_emotions');
    }
    public function updateEventEmotion($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('event_emotions', $data);
    }

    public function insertEventFan($data) {
        $this->db->insert('event_fans', $data);
    }
    public function deleteEventFan($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_fans');
    }

    public function insertEventNotification($data) {
        $this->db->insert('event_notifications', $data);
    }
    public function deleteEventNotification($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_notifications');
    }

    public function insertEventSuggestion($data) {
        $this->db->insert('event_suggestions', $data);
    }
    public function deleteEventSuggestion($id) {
        $this->db->where('id', $id);
        $this->db->delete('event_suggestions');
    }
}