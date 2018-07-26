<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $events = $this->events_model->getEventsByCategoryIds($category_ids, 0);
        $html = '';
        if (count($category_ids) == 0) {
            $html .= "<h3 class='centered'>Все события</h3>";
        } else {
            $html .= "<h3 class='centered'>Результаты по выбранным категориям</h3>";
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
            'events_by_categories' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_event_category() {
        $category_name = $this->input->post('category_name');

        $data_event_categories = array(
            'category_name' => $category_name,
        );
        $this->events_model->insertEventCategory($data_event_categories);
    }

    public function delete_event_category() {
        $id = $this->input->post('id');
        $this->events_model->deleteEventCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_event_categories = array(
            'category_name' => $category_name,
        );
        $this->events_model->updateEventCategoryById($id, $data_event_categories);
    }
}