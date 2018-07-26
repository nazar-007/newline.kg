<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $event_id = $this->input->post('event_id');
        $event_emotions = $this->events_model->getEventEmotionsByEventId($event_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($event_emotions) == 0 ) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($event_emotions as $event_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$event_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$event_emotion->main_image' class='action_avatar'>
                            </div>
                            <div class='emotion_user_name'>
                                $event_emotion->nickname $event_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_event_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }

    public function insert_event_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $event_id = $this->input->post('event_id');
        $emotion_num_rows = $this->events_model->getEventEmotionNumRowsByEventIdAndEmotionedUserId($event_id, $emotioned_user_id);

        if ($emotion_num_rows == 0 && $emotioned_user_id == $session_user_id) {
            $data_event_emotions = array(
                'emotion_date' => $emotion_date,
                'emotion_time' => $emotion_time,
                'emotioned_user_id' => $emotioned_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventEmotion($data_event_emotions);

            $user_name = $this->users_model->getNicknameAndSurnameById($emotioned_user_id);
            $event_name = $this->events_model->getEventNameById($event_id);

            $data_event_actions = array(
                'event_action' => "$user_name поставил(-а) эмоцию на событие '$event_name'",
                'event_time_unix' => time(),
                'action_user_id' => $emotioned_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventAction($data_event_actions);

            $total_emotions = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_emotions');
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы уже ставили эмоцию на данное событие или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_event_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $event_id = $this->input->post('event_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->events_model->getEventEmotionNumRowsByEventIdAndEmotionedUserId($event_id, $emotioned_user_id);

        if ($emotion_num_rows > 0 && $emotioned_user_id == $session_user_id) {
            $this->events_model->deleteEventEmotionByEventIdAndEmotionedUserId($event_id, $emotioned_user_id);
            $total_emotions = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_emotions');
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на данное событие или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}