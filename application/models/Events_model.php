<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_model extends CI_Model {
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
    public function getEvents() {
        $query = $this->db->get('events');
        return $query->result();
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
    public function getEventsByCategoryIds($category_ids, $offset) {
        $this->db->order_by('id DESC');
        $limit = 12;
        $this->db->limit($limit, $offset);
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
    public function getEventCommentNumRowsByIdAndCommentedUserId($id, $commented_user_id) {
        $this->db->where('id', $id);
        $this->db->where('commented_user_id', $commented_user_id);
        $query = $this->db->get('event_comments');
        return $query->num_rows();
    }
    public function getEventCommentsByEventId($event_id) {
        $this->db->select('event_comments.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('event_comments');
        $this->db->join('users', 'event_comments.commented_user_id = users.id');
        $this->db->order_by('comment_date DESC, comment_time DESC');
        $this->db->where('event_comments.event_id', $event_id);
        $query = $this->db->get();
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
    public function getEventComplaintNumRowsByEventIdAndComplainedUserId($song_id, $complained_user_id) {
        $this->db->where('event_id', $song_id);
        $this->db->where('complained_user_id', $complained_user_id);
        $query = $this->db->get('event_complaints');
        return $query->num_rows();
    }
    public function getEventEmotionNumRowsByEventIdAndEmotionedUserId($event_id, $emotioned_user_id) {
        $this->db->where('event_id', $event_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
        $query = $this->db->get('event_emotions');
        return $query->num_rows();
    }
    public function getEventEmotionsByEventId($event_id) {
        $this->db->select('event_emotions.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('event_emotions');
        $this->db->join('users', 'event_emotions.emotioned_user_id = users.id');
        $this->db->order_by('emotion_date DESC, emotion_time DESC');
        $this->db->where('event_emotions.event_id', $event_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getEventFanNumRowsByEventIdAndFanUserId($event_id, $fan_user_id) {
        $this->db->where('event_id', $event_id);
        $this->db->where('fan_user_id', $fan_user_id);
        $query = $this->db->get('event_fans');
        return $query->num_rows();
    }
    public function getEventFansByEventId($event_id) {
        $this->db->select('event_fans.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('event_fans');
        $this->db->join('users', 'event_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('event_fans.event_id', $event_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getEventFansByFanUserId($fan_user_id) {
        $this->db->select('event_fans.*, events.event_name, events.event_start_date, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('event_fans');
        $this->db->join('events', 'event_fans.event_id = events.id');
        $this->db->join('users', 'event_fans.fan_user_id = users.id');
        $this->db->order_by('fan_date DESC, fan_time DESC');
        $this->db->where('event_fans.fan_user_id', $fan_user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getEventNameById($id) {
        $this->db->select('id, event_name');
        $this->db->where('id', $id);
        $query = $this->db->get('events');
        $events = $query->result();
        foreach ($events as $event) {
            $event_name = $event->event_name;
        }
        return $event_name;
    }
    public function getEventNumRowsById($id) {
        $this->db->select('id');
        $this->db->where('id', $id);
        $query = $this->db->get('events');
        return $query->num_rows();
    }
    public function getEventSuggestionsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('event_suggestions');
        return $query->result();
    }
    public function getOneEventById($id) {
        $this->db->select('events.*, event_categories.category_name');
        $this->db->from('events');
        $this->db->join('event_categories', 'events.category_id = event_categories.id');
        $this->db->where('events.id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getTotalByEventIdAndEventTable($event_id, $event_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('event_id');
        $this->db->where('event_id', $event_id);
        $query = $this->db->get($event_table);
        $events = $query->result();
        foreach ($events as $event) {
            $total = $event->total;
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
    public function getTotalCommonEventsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT COUNT(*) as total
            FROM event_fans 
            WHERE event_id IN 
            (SELECT event_id 
            FROM event_fans 
            WHERE fan_user_id 
            IN ($user1,$user2) 
            GROUP BY event_id 
            HAVING COUNT(event_id) = 2) 
            AND fan_user_id = $user1"
        );
        $events = $query->result();
        foreach ($events as $event) {
            $total = $event->total;
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
    public function getCommonEventsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT event_fans.*, events.event_name, events.event_start_date
                FROM event_fans
                INNER JOIN events ON(event_fans.event_id = events.id)
                WHERE event_id IN
                (SELECT event_id
                FROM event_fans
                WHERE fan_user_id IN ($user1,$user2)
                GROUP BY event_id
                HAVING COUNT(event_id) = 2)
                AND event_fans.fan_user_id = $user1"
        );
        return $query->result();
    }
    public function searchEventsByEventName($event_name) {
        $this->db->like('event_name', $event_name);
        $query = $this->db->get('events');
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
    public function deleteEventEmotionByEventIdAndEmotionedUserId($event_id, $emotioned_user_id) {
        $this->db->where('event_id', $event_id);
        $this->db->where('emotioned_user_id', $emotioned_user_id);
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
    public function deleteEventFanByEventIdAndFanUserId($event_id, $fan_user_id) {
        $this->db->where('event_id', $event_id);
        $this->db->where('fan_user_id', $fan_user_id);
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