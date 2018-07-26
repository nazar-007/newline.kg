<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $category_ids = array();
        if (isset($_POST['offset'])) {
            $offset = $this->input->post('offset');
        } else {
            $offset = 0;
        }
        $events = $this->events_model->getEventsByCategoryIds($category_ids, $offset);
        $html = '';
        foreach ($events as $event) {
            $event_id = $event->id;
            $event_name = $event->event_name;
            $event_date = $event->event_start_date;
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
            $total_event_emotions = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_emotions');
            $total_event_fans = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_fans');
            $html .= "<div class='list col-xs-6 col-sm-6 col-md-4 col-lg-4 event'>
                    <div class='col-xs-6 centered event-date'>
                        <a href='" . base_url() . "one_event/$event_id'>
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
                        </div>
                    </a>
                    <div class='col-xs-6 centered'>
                        <div class='actions'>
                            <div class='emotions_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                                <span class='badge' onclick='getEventEmotions(this)' data-event_id='$event->id' data-toggle='modal' data-target='#getEventEmotions'>$total_event_emotions</span>
                            </div>
                            <div class='fans_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                                <span class='badge' onclick='getEventFans(this)' data-event_id='$event->id' data-toggle='modal' data-target='#getEventFans'>$total_event_fans</span>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-12 event-name'>
                            <a href='" . base_url() . "one_event/$event_id'>
                                $event_name
                            </a>
                        </div>
                    </div>
                </div>";
        }

        if (isset($_POST['offset'])) {
            $data = array(
                'events' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            echo json_encode($data);
        } else {
            $friend_ids = array();
            $friends = $this->users_model->getFriendsByUserId($session_user_id);
            foreach ($friends as $friend) {
                $friend_ids[] = $friend->friend_id;
            }
            $data = array(
                'events' => $html,
                'friend_ids' => $friend_ids,
                'event_actions' => $this->events_model->getEventActionsByFriendIds($friend_ids),
                'event_categories' => $this->events_model->getEventCategories(),
                'my_fan_events' => $this->events_model->getEventFansByFanUserId($session_user_id),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('events', $data);
        }
    }

    public function One_event($id) {
        $event_num_rows = $this->events_model->getEventNumRowsById($id);
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        if ($event_num_rows == 1) {
            $data_events = array(
                'current_id' => $id,
                'one_event' => $this->events_model->getOneEventById($id),
                'complaint_num_rows' => $this->events_model->getEventComplaintNumRowsByEventIdAndComplainedUserId($id, $session_user_id),
                'emotion_num_rows' => $this->events_model->getEventEmotionNumRowsByEventIdAndEmotionedUserId($id, $session_user_id),
                'fan_num_rows' => $this->events_model->getEventFanNumRowsByEventIdAndFanUserId($id, $session_user_id),
                'total_emotions' => $this->events_model->getTotalByEventIdAndEventTable($id, 'event_emotions'),
                'total_comments' => $this->events_model->getTotalByEventIdAndEventTable($id, 'event_comments'),
                'total_fans' => $this->events_model->getTotalByEventIdAndEventTable($id, 'event_fans'),
                'event_num_rows' => $event_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_events = array(
                'current_id' => $id,
                'event_num_rows' => $event_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('one_event', $data_events);
    }

    public function search_events() {
        $search_by_name = $this->input->post('search_by_name');
        $html = '';
        if (iconv_strlen($search_by_name) > 0) {
            $html .= "<h3 class='centered'>Результаты по запросу $search_by_name</h3>";
            $events = $this->events_model->searchEventsByEventName($search_by_name);
            if (count($events) == 0) {
                $html .= "<div class='red centered'>По Вашему запросу $search_by_name ничего не найдено! :(</div>";
            }
        } else {
            $html .= "<h3 class='centered'>Все события</h3>";
            $events = $this->events_model->getEventsByCategoryIds(array(), 0);
        }
        foreach ($events as $event) {
            $event_id = $event->id;
            $event_name = $event->event_name;
            $event_date = $event->event_start_date;
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
            $total_event_emotions = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_emotions');
            $total_event_fans = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_fans');
            $html .= "<div class='list col-xs-6 col-sm-6 col-md-4 col-lg-4 event'>
                    <div class='col-xs-6 centered event-date'>
                        <a href='" . base_url() . "one_event/$event_id'>
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
                        </div>
                    </a>
                    <div class='col-xs-6 centered'>
                        <div class='actions'>
                            <div class='emotions_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                                <span class='badge' onclick='getEventEmotions(this)' data-event_id='$event->id' data-toggle='modal' data-target='#getEventEmotions'>$total_event_emotions</span>
                            </div>
                            <div class='fans_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                                <span class='badge' onclick='getEventFans(this)' data-event_id='$event->id' data-toggle='modal' data-target='#getEventFans'>$total_event_fans</span>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-xs-12 event-name'>
                            <a href='" . base_url() . "one_event/$event_id'>
                                $event_name
                            </a>
                        </div>
                    </div>
                </div>";
        }
        $data = array(
            'search_events' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }


    public function insert_event() {
        $event_name = $this->input->post('event_name');
        $event_description = $this->input->post('event_description');
        $event_address = $this->input->post('book_author');
        $event_start_date = $this->input->post('event_start_date');
        $event_start_time = $this->input->post('event_start_time');
        $category_id = $this->input->post('category_id');
        $user_id = $this->input->post('suggested_user_id');

        $data_events = array(
            'event_name' => $event_name,
            'event_description' => $event_description,
            'event_address' => $event_address,
            'event_start_date' => $event_start_date,
            'event_start_time' => $event_start_time,
            'category_id' => $category_id,
            'user_id' => $user_id
        );

        $this->events_model->insertEvent($data_events);
        $insert_event_id = $this->db->insert_id();

        // НАДО ДОДЕЛАТЬ ЭКШН

        $event_action = 'Предложенное событие Назара "Встреча крутых айтишников" одобрил админ.';
        $data_event_actions = array(
            'event_action' => $event_action,
            'event_time_unix' => time(),
            'action_user_id' => $user_id,
            'event_id' => $insert_event_id
        );
        $this->events_model->insertEventAction($data_event_actions);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        if ($user_id != 0) {
            $notification_text = 'Админ одобрил Вашу книгу "Убить пересмешника". К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашей книги',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => $insert_event_id,
                'link_table' => 'events',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before + 1,
                'rating' => $rating_before + 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);

            $suggestion_id = $this->input->post('suggestion_id');
            $this->events_model->deleteEventSuggestionById($suggestion_id);
        }
    }

    public function delete_event() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');

        $this->events_model->deleteEventActionsByBookId($id);
        $this->events_model->deleteEventCommentsByBookId($id);
        $this->events_model->deleteEventComplaintsByBookId($id);
        $this->events_model->deleteEventEmotionsByBookId($id);
        $this->events_model->deleteEventFansByBookId($id);
        $this->events_model->deleteEventById($id);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        if ($user_id != 0) {
            $notification_text = 'Ваше одобренное событие "Встреча крутых айтишников" удалено. С Вашей валюты снялся 1 сом, а с рейтинга - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Удаление Вашего одобренного события',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before - 1,
                'rating' => $rating_before - 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);
        }

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event() {
        $id = $this->input->post('id');
        $event_name = $this->input->post('event_name');
        $event_description = $this->input->post('event_description');
        $event_address = $this->input->post('book_author');
        $event_start_date = $this->input->post('event_start_date');
        $event_start_time = $this->input->post('event_start_time');

        $data_events = array(
            'event_name' => $event_name,
            'event_description' => $event_description,
            'event_address' => $event_address,
            'event_start_date' => $event_start_date,
            'event_start_time' => $event_start_time
        );

        $this->events_model->updateEventById($id, $data_events);
    }
}