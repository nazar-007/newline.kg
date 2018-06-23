<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $event_id = $this->input->post('event_id');
        $data = array(
            'event_comments' => $this->events_model->getEventCommentsByEventId($event_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_comments', $data);
    }

    public function insert_event_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $event_id = $this->input->post('event_id');

        $data_event_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'user_id' => $user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventComment($data_event_comments);
    }
}