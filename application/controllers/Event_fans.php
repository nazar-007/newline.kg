<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $event_id = 3;
        $data = array(
            'event_fans' => $this->events_model->getEventFansByEventId($event_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_fans', $data);
    }

    public function insert_event_fan() {
        $fan_date = date('d.m.Y');
        $fan_time = date('H:i:s');
        $fan_user_id = $this->input->post('fan_user_id');
        $event_id = $this->input->post('event_id');

        $data_event_fans = array(
            'fan_date' => $fan_date,
            'fan_time' => $fan_time,
            'fan_user_id' => $fan_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventFan($data_event_fans);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $event_action = 'Пользователь Назар пойдёт на событие "Встреча крутых IT-специалистов"';

        $data_event_actions = array(
            'event_action' => $event_action,
            'event_time_unix' => time(),
            'action_user_id' => $fan_user_id,
            'event_id' => $event_id
        );
        $this->events_model->insertEventAction($data_event_actions);
    }

    public function delete_event_fan() {

        // НАДО ДОДЕЛАТЬ!!!

        $id = $this->input->post('id');
        $this->events_model->deleteEventFanById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}