<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'event_suggestions' => $this->events_model->getEventSuggestionsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('event_suggestions', $data);
    }

    public function insert_event_suggestion() {
        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $event_name = $this->input->post('event_name');
        $event_description = $this->input->post('event_description');
        $day = $this->input->post('day');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $hour = $this->input->post('hour');
        $minute = $this->input->post('minute');

        if (strlen($day) == 1) {
            $day = "0" . $day;
        } else {
            $day = $this->input->post('day');
        }
        if (strlen($month) == 1) {
            $month = "0" . $month;
        } else {
            $month = $this->input->post('month');
        }
        if (strlen($hour) == 1) {
            $hour = "0" . $hour;
        } else {
            $hour = $this->input->post('hour');
        }
        if (strlen($minute) == 1) {
            $minute = "0" . $minute;
        } else {
            $minute = $this->input->post('minute');
        }
        $event_start_date = $day . "." . $month . "." . $year;
        $event_start_time = $hour . ":" . $minute . ":00";
        $event_address = $this->input->post('event_address');
        $category_id = $this->input->post('category_id');
        $suggestion_json = "[{'event_name': '$event_name', 'event_description': '$event_description',
           'event_address': '$event_address', 'event_start_date': '$event_start_date', 'event_start_time': '$event_start_time', 'category_id': '$category_id'}]";
        $suggestion_date = date('d.m.Y');
        $suggestion_time = date('H:i:s');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('events');
        $suggested_user_id = $_SESSION['user_id'];

        $data_event_suggestions = array(
            'suggestion_json' => $suggestion_json,
            'suggestion_date' => $suggestion_date,
            'suggestion_time' => $suggestion_time,
            'admin_id' => $admin_id,
            'suggested_user_id' => $suggested_user_id
        );
        $this->events_model->insertEventSuggestion($data_event_suggestions);
        $messages['success_suggestion'] = 'Событие ' . $event_name . ' успешно предложено Вами, отправлено админу и будет рассмотрено в ближайшее время';
        echo json_encode($messages);
    }

    public function delete_event_suggestion() {
        $id = $this->input->post('id');
        $event_name = $this->input->post('event_name');
        $user_id = $this->input->post('user_id');
        $this->events_model->deleteEventSuggestionById($id);
        $notification_text = 'Админ не одобрил Ваше предложенное событие ' . $event_name . '.';

        $data_user_notifications = array(
            'notification_type' => 'Отказ от предложенного события',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'link_id' => 0,
            'link_table' => 0,
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_event_suggestion() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_book_suggestions = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->books_model->updateEventSuggestionById($id, $data_book_suggestions);
    }
}