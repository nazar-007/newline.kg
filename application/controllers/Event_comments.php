<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $event_id = $this->input->post('event_id');
        $event_comments = $this->events_model->getEventCommentsByEventId($event_id);
        $session_user_id = $_SESSION['user_id'];
        $session_user_email = $_SESSION['user_email'];
        $html = '';
        $csrf_hash = $this->security->get_csrf_hash();
        $html .= "<form action='javascript:void(0)' onsubmit='insertEventComment(this)'>
                    <input type='hidden' class='csrf' name='csrf_test_name' value='$csrf_hash'>
                    <textarea required id='comment_text' class='form-control comment-input' placeholder='Добавить коммент' name='comment_text'></textarea>
                    <input class='commented_user_id' type='hidden' name='commented_user_id' value='$session_user_id'>
                    <input class='event_id' type='hidden' name='event_id' value='$event_id'>
                    <button class='btn btn-success center-block' type='submit'>Комментировать</button>
                  </form>
                  <div class='comments_by_event'>";
        if (count($event_comments) > 0) {
            foreach ($event_comments as $event_comment) {
                $html .= "<div class='one_comment_$event_comment->id'>
                            <div class='commented_user'>
                                <a href='" . base_url() . "one_user/$event_comment->email'>
                                    <img src='" . base_url() . "uploads/images/user_images/" . $event_comment->main_image . "' class='commented_avatar'>
                                    $event_comment->nickname $event_comment->surname 
                                </a>
                                <span class='comment-date-time'>$event_comment->comment_date <br> $event_comment->comment_time</span>";
                if ($event_comment->email == $session_user_email) {
                    $html .= "<div onclick='deleteEventComment(this)' data-event_comment_id='$event_comment->id' data-event_id='$event_id' class='right'>X</div>";
                }
                $html .= "</div>
                    <div class='comment_text'>
                       $event_comment->comment_text
                    </div>
                </div>";
            }
        }
        $html .= "</div>";
        $get_comments_json = array(
            'one_event_comments' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_comments_json);
    }

    public function insert_event_comment() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $commented_user_id = $this->input->post('commented_user_id');
        $event_id = $this->input->post('event_id');

        if ($comment_text != '' && $commented_user_id == $session_user_id) {
            $data_event_comments = array(
                'comment_text' => $comment_text,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'commented_user_id' => $commented_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventComment($data_event_comments);

            $insert_comment_id = $this->db->insert_id();

            $total_comments = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_comments');
            $user_email = $this->users_model->getEmailById($commented_user_id);
            $user_name = $this->users_model->getNicknameAndSurnameById($commented_user_id);
            $user_image = $this->users_model->getMainImageById($commented_user_id);
            $event_name = $this->events_model->getEventNameById($event_id);

            $data_event_actions = array(
                'event_action' => "$user_name оставил(-а) коммент на событие '$event_name'",
                'event_time_unix' => time(),
                'action_user_id' => $commented_user_id,
                'event_id' => $event_id
            );
            $this->events_model->insertEventAction($data_event_actions);

            $insert_json = array(
                'comment_id' => $insert_comment_id,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'comment_text' => $comment_text,
                'total_comments' => $total_comments,
                'user_email' => $user_email,
                'user_name' => $user_name,
                'user_image' => $user_image,
                'event_id' => $event_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'comment_text' => $comment_text,
                'comment_error' => "Вы ввели пустой коммент или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_event_comment() {
        $id = $this->input->post('id');
        $event_id = $this->input->post('event_id');
        $session_user_id = $_SESSION['user_id'];
        $comment_num_rows = $this->events_model->getEventCommentNumRowsByIdAndCommentedUserId($id, $session_user_id);
        if ($comment_num_rows > 0) {
            $this->events_model->deleteEventCommentById($id);
            $total_comments = $this->events_model->getTotalByEventIdAndEventTable($event_id, 'event_comments');
            $delete_json = array(
                'total_comments' => $total_comments,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'comment_error' => "Не удалось удалить коммент или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_event_comment_by_admin() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] && $_SESSION['admin_table']) {
            $this->events_model->deleteEventCommentById($id);

            $data_admin_actions = array(
                'admin_action' => "$admin_email удалил коммент с текстом $comment_text под id $id",
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
                'comment_error' => 'У вас нет прав на удаление комментов в событии',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
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