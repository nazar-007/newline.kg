<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'event_notifications' => $this->events_model->getEventNotificationsByFriendIds($friend_ids),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_notifications', $data);
    }

}