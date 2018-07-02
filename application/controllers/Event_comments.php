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
        $commented_user_id = $this->input->post('commented_user_id');
        $event_id = $this->input->post('event_id');

        $data_event_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'commented_user_id' => $commented_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventComment($data_event_comments);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $event_action = 'Пользователь Назар прокомментировал событие "Встреча крутых IT-специалистов"';

        $data_event_actions = array(
            'event_action' => $event_action,
            'event_time_unix' => time(),
            'action_user_id' => $commented_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventAction($data_event_actions);
    }

    public function delete_event_comment() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventCommentById($id);
        $this->events_model->deleteEventCommentComplaintsByEventCommentId($id);
        $this->events_model->deleteEventCommentEmotionsByEventCommentId($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event_comment() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');

        $data_event_comments = array(
            'comment_text' => $comment_text
        );
        $this->events_model->updateEventCommentById($id, $data_event_comments);
    }
}