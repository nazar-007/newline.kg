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
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'event' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ событий.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Пользователь</td>
                    <td>Проверка события</td>
                    <td>Удалить предложение</td>
                    <td>Принять предложение</td>
                </tr>";

            $event_suggestions = $this->events_model->getEventSuggestionsByAdminId($admin_id);

            foreach ($event_suggestions as $event_suggestion) {
                $id = $event_suggestion->id;
                $suggestion_json = $event_suggestion->suggestion_json;
                $suggested_user_id = $event_suggestion->suggested_user_id;
                $email = $event_suggestion->email;
                $html .= "<tr class='one-suggestion-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>
                            <button onclick='getOneEventSuggestionByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneEventSuggestion' data-id='$id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressEventSuggestion(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteEventSuggestion' data-id='$id'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressEventSuggestionAndInsertPressEvent(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteEventSuggestionAndInsertEvent' data-id='$id' data-suggested_user_id='$suggested_user_id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'event_suggestions' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'suggestion_error' => 'У вас нет прав на просмотр предложений на события',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
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
        $suggestion_json = "[{\"event_name\": \"$event_name\", \"event_description\": \"$event_description\",
           \"event_address\": \"$event_address\", \"event_start_date\": \"$event_start_date\", \"event_start_time\": \"$event_start_time\", \"category_id\": \"$category_id\"}]";
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
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $this->events_model->deleteEventSuggestionById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил предложение на добавление события'",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'suggestion_error' => 'Не удалось удалить предложение',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_event_suggestion_and_insert_event() {
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $this->events_model->deleteEventSuggestionById($id);

            $event_name = $this->input->post('event_name');
            $event_description = $this->input->post('event_description');
            $event_address = $this->input->post('event_address');
            $event_start_date = $this->input->post('event_start_date');
            $event_start_time = $this->input->post('event_start_time');
            $category_id = $this->input->post('category_id');
            $suggested_user_id = $this->input->post('suggested_user_id');

            $data_events = array(
                'event_name' => $event_name,
                'event_description' => $event_description,
                'event_address' => $event_address,
                'event_start_date' => $event_start_date,
                'event_start_time' => $event_start_time,
                'category_id' => $category_id,
            );

            $this->events_model->insertEvent($data_events);
            $insert_event_id = $this->db->insert_id();

            $data_admin_actions = array(
                'admin_action' => "$admin_email добавил событие '$event_name'",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);

            $user_name = $this->users_model->getNicknameAndSurnameById($suggested_user_id);

            $data_event_actions = array(
                'event_action' => "Предложенное событие $event_name пользователя $user_name опубликовали админы.",
                'event_time_unix' => time(),
                'action_user_id' => $suggested_user_id,
                'event_id' => $insert_event_id
            );
            $this->events_model->insertEventAction($data_event_actions);

            $notification_date = date('d.m.Y');
            $notification_time = date('H:i:s');
            $notification_text = "Админ одобрил Ваше предложенное событие $event_name. К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.";

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашего события',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => $insert_event_id,
                'link_table' => 'events',
                'user_id' => $suggested_user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($suggested_user_id);
            $rating_before = $this->users_model->getRatingById($suggested_user_id);

            $data_users = array(
                'currency' => $currency_before + 1,
                'rating' => $rating_before + 5
            );
            $this->users_model->updateUserById($suggested_user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($suggested_user_id);
            $rank_after = $this->users_model->getRankById($suggested_user_id);
            $this->users_model->updateRankById($suggested_user_id, $rating_after, $rank_after);

            $insert_json = array(
                'id' => $id,
                'suggestion_success' => 'Событие добавлено',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'suggestion_error' => 'Не удалось добавить событие',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }
}