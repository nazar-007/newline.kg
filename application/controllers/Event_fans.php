<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $event_id = $this->input->post('event_id');
        $event_fans = $this->events_model->getEventFansByEventId($event_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($event_fans) == 0) {
            $html .= "<h3 class='centered'>Пока никто не добавлял это в любимки.</h3>";
        } else {
            foreach ($event_fans as $event_fan) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 fan_user'>
                        <a href='" . base_url() . "one_user/$event_fan->email'>
                            <div class='fan_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$event_fan->main_image' class='action_avatar'>
                            </div>
                            <div class='fan_user_name'>
                                $event_fan->nickname $event_fan->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_fans_json = array(
            'one_event_fans' => $html,
            'my_fan_events' => $this->events_model->getEventFansByFanUserId($session_user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_fans_json);
    }

    public function common_events() {
        $this->load->view('session_user');
        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');
        $common_events = $this->events_model->getCommonEventsByTwoUsers($user_id, $friend_id);
        $html = '';
        foreach ($common_events as $common_event) {
            $event_id = $common_event->id;
            $event_name = $common_event->event_name;
            $event_date = $common_event->event_start_date;
            $day = $event_date[0] . $event_date[1];
            $year = $event_date[6] . $event_date[7] . $event_date[8] . $event_date[9];
            if ($event_date[3] == '0') {
                $month_index = $event_date[4];
            } else {
                $month_index = $event_date[3] . $event_date[4];
            }
            $months_array = array(
                "1"=>"Января","2"=>"Февраля","3"=>"Марта",
                "4"=>"Апреля","5"=>"Мая", "6"=>"Июня",
                "7"=>"Июля","8"=>"Августа","9"=>"Сентября",
                "10"=>"Октября","11"=>"Ноября","12"=>"Декабря"
            );
            $month = $months_array[$month_index];
            $html.= "<div class='list col-xs-6 col-sm-6 col-md-6 col-lg-6 event'>
                        <div class='centered'>
                        <div class='event-date'>
                        <a href='" . base_url() . "one_event/$common_event->event_id'>
                            <div class='date'>
                                $day
                            </div>
                            <br>
                            <div class='date'>
                                $month
                            </div>
                            <br>
                            <div class='date'>
                                $year
                            </div>
                        </a>
                        </div>
                        <div class='event-name'>
                            $event_name
                        </div>
                    </div>
                    </div>";
        }
        $get_common_json = array(
            'common_events' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_common_json);

    }

    public function insert_event_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $fan_date = date("d.m.Y");
        $fan_time = date("H:i:s");
        $fan_user_id = $this->input->post('fan_user_id');
        $event_id = $this->input->post('event_id');

        $fan_num_rows = $this->events_model->getEventFanNumRowsByEventIdAndFanUserId($event_id, $fan_user_id);

        if ($fan_num_rows == 0 && $fan_user_id == $session_user_id) {
            $data_event_fans = array(
                'fan_date' => $fan_date,
                'fan_time' => $fan_time,
                'fan_user_id' => $fan_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventFan($data_event_fans);

            $user_name = $this->users_model->getNicknameAndSurnameById($fan_user_id);
            $event_name = $this->events_model->getEventNameById($event_id);

            $data_event_actions = array(
                'event_action' => "$user_name планирует посетить событие '$event_name'",
                'event_time_unix' => time(),
                'action_user_id' => $fan_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventAction($data_event_actions);

            $total_fans = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_fans');
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы уже добавляли это событие в любимки или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_event_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $event_id = $this->input->post('event_id');
        $fan_user_id = $this->input->post('fan_user_id');
        $fan_num_rows = $this->events_model->getEventFanNumRowsByEventIdAndFanUserId($event_id, $fan_user_id);

        if ($fan_num_rows > 0 && $fan_user_id == $session_user_id) {
            $this->events_model->deleteEventFanByEventIdAndFanUserId($event_id, $fan_user_id);
            $total_fans = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_fans');
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы ещё не добавляли данную книгу в любимки или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}