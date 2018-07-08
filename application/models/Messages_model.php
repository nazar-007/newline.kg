<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function getPrivateMessagesByUserIdAndTalkerId($user_id, $talker_id) {
        $this->db->or_where('imagine_id', $user_id);
        $this->db->where('talker_id', $talker_id);
        $this->db->or_where('talker_id', $user_id);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('user_id', $talker_id);

        $query = $this->db->get('private_messages');
        $messages = $query->result();

        echo "<pre>";
        print_r($messages);
        echo "</pre>";

        foreach ($messages as $message) {
            echo "<h3>". $message->message_text . " " . $message->user_id . "</h3><br>";
        }
    }
    public function getGuestMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('guest_messages');
        return $query->result();
    }
    public function getFeedbackMessages() {
        $query = $this->db->get('feedback_messages');
        return $query->result();
    }
//  SELECT * FROM `private_messages` WHERE imagine_id = 1 AND talker_id = 2 OR talker_id = 1 AND imagine_id = 1 AND user_id = 1 OR user_id = 2 AND imagine_id = 1
//  SELECT * FROM `private_messages` WHERE talker_id = 2 AND imagine_id = 1 OR talker_id = 1 AND imagine_id = 1 AND user_id = 1 OR user_id = 2 AND imagine_id = 1
//    public function getPrivateMessagesByUserIdAndTalkerId($user_id, $talker_id) {
//        $this->db->where('user_id', $user_id);
//        $this->db->where('user_id', $talker_id);
//        $this->db->where('talker_id', $talker_id);
//        $this->db->or_where('imagine_id', $user_id);
//        $query = $this->db->get('private_messages');
//        $messages = $query->result();
//
//        echo "<pre>";
//        print_r($messages);
//        echo "</pre>";
//
//        foreach ($messages as $message) {
//            echo "<h3>". $message->message_text . " " . $message->user_id . "</h3><br>";
//        }
//    }

    public function insertPrivateMessage($data) {
        $this->db->insert('private_messages', $data);
    }
    public function insertGuestMessage($data) {
        $this->db->insert('guest_messages', $data);
    }
    public function insertFeedbackMessage($data) {
        $this->db->insert('feedback_messages', $data);
    }

    public function deletePrivateMessage($id) {
        $this->db->where('id', $id);
        $this->db->delete('private_messages');
    }
    public function deletePrivateMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('private_messages');
    }
    public function deletePrivateMessagesByUserIdOrTalkerId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('talker_id', $user_id);
        $this->db->delete('private_messages');
    }
    public function deleteGuestMessage($id) {
        $this->db->where('id', $id);
        $this->db->delete('guest_messages');
    }
    public function deleteGuestMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('guest_messages');
    }
    public function deleteGuestMessagesByUserIdOrGuestId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('guest_id', $user_id);
        $this->db->delete('guest_messages');
    }
    public function deleteFeedbackMessageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('feedback_messages');
    }
    public function deleteFeedbackMessagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('feedback_messages');
    }
    public function deleteAllFeedbackMessages() {
        $this->db->delete('feedback_messages');
    }

    public function updateGuestMessageById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('guest_messages', $data);

    }
}