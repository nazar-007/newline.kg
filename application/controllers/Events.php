<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEventsByCategoryIds($category_ids),
            'event_categories' => $this->events_model->getEventCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('events', $data);
    }

    public function choose_event_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $events = $this->events_model->getEventsByCategoryIds($category_ids);
        foreach ($events as $event) {
            echo "<tr>
            <td>$event->id</td>
            <td>
                <a href='" . base_url() . "models/" . $event->id . "'>" . $event->event_name . "</a>
            </td>
         </tr>";
        }
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

        $event_comments = $this->events_model->getEventCommentsByEventId($id);
        foreach ($event_comments as $event_comment) {
            $event_comment_id = $event_comment->id;
            $this->events_model->deleteEventCommentComplaintsByEventCommentId($event_comment_id);
            $this->events_model->deleteEventCommentEmotionsByEventCommentId($event_comment_id);
        }
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