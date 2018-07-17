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
//        foreach ($events as $event) {
//            $book_id = $book->id;
//            $book_name = $book->book_name;
//            $total_book_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
//            $total_book_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
//            $html .= "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
//                    <a href='" . base_url() . "one_book/$book_id'>
//                        <div class='book_cover'>
//                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$book->book_image'>
//                        </div>
//                        <div class='book_name'>$book_name</div>
//                    </a>
//                    <div class='actions'>
//                        <span class='emotions_field'>
//                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
//                            <span class='badge' onclick='getBookEmotions(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookEmotions'>$total_book_emotions</span>
//                        </span>
//                        <span class='fans_field'>
//                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
//                            <span class='badge' onclick='getBookFans(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookFans'>$total_book_fans</span>
//                        </span>
//                    </div>
//                </div>";
//        }

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
                'event_actions' => $this->events_model->getEventActionsByFriendIds($friend_ids),
                'event_categories' => $this->events_model->getEventCategories(),
                'my_fan_events' => $this->events_model->getEventFansByFanUserId($session_user_id),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('events', $data);
        }
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

    public function One_event($id) {
        $event_num_rows = $this->events_model->getEventNumRowsById($id);
        if ($event_num_rows == 1) {
            $data_events = array(
                'one_event' => $this->events_model->getOneEventById($id),
                'event_num_rows' => $event_num_rows
            );
        } else {
            echo "Событие либо прошло и удалено, либо ещё не добавлено!";
            $data_events = array(
                'event_num_rows' => $event_num_rows
            );
        }
        $this->load->view('one_event', $data_events);
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