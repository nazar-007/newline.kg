<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('messages_model');
        $this->load->model('users_model');
    }
    public function Index() {
        $user_id = $this->input->post('user_id');
        $guest_id = $this->input->post('guest_id');
        $guest_messages = $this->messages_model->getGuestMessagesByUserId($user_id);
        $session_user_id = $_SESSION['user_id'];
        $html = '';
        $csrf_hash = $this->security->get_csrf_hash();

        if ($user_id != $session_user_id) {
            $html .= "<form action='javascript:void(0)' onsubmit='insertGuestMessage(this)'>
                    <input type='hidden' class='csrf' name='csrf_test_name' value='$csrf_hash'>
                    <textarea required id='message_text' class='form-control comment-input' placeholder='Добавить запись в гостевую книгу...' name='message_text'></textarea>
                    <input class='user_id' type='hidden' name='user_id' value='$user_id'>
                    <input class='guest_id' type='hidden' name='guest_id' value='$guest_id'>
                    <button class='btn btn-success center-block' type='submit'>Добавить запись</button>
                  </form>";
        }

                  $html .= "<div class='messages_by_user'>";
        if (count($guest_messages) == 0) {
            $html .= "<h3 class='centered no-messages'>Записей в гостевой книге нет.</h3>";
        } else {
            foreach ($guest_messages as $guest_message) {
                $message_num_rows = $this->messages_model->getGuestMessageNumRowsByIdAndGuestId($guest_message->id, $guest_id);
                $html .= "<div class='one_message_$guest_message->id'>
                            <div class='commented_user'>
                                <a href='" . base_url() . "one_user/$guest_message->email'>
                                    <img src='" . base_url() . "uploads/images/user_images/" . $guest_message->main_image . "' class='commented_avatar'>
                                    $guest_message->nickname $guest_message->surname 
                                </a>
                                <span class='comment-date-time'>$guest_message->message_date <br> $guest_message->message_time</span>";
                if ($user_id == $session_user_id || $message_num_rows > 0) {
                    $html .= "<div onclick='deleteGuestMessage(this)' data-id='$guest_message->id' data-guest_id='$guest_message->guest_id' class='right'>X</div>";
                }
                $html .= "</div>
                                        <div class='comment_text'>
                                           $guest_message->message_text
                                        </div>
                                    </div>";
            }
        }
        $html .= "</div>";
        $data = array(
            'guest_messages' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_guest_message() {
        $message_text = $this->input->post('message_text');
        $message_date = date("d.m.Y");
        $message_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $guest_id = $this->input->post('guest_id');
        $session_user_id = $_SESSION['user_id'];

        if ($guest_id == $session_user_id && $message_text != '') {
            $data_guest_messages = array(
                'message_text' => $message_text,
                'message_date' => $message_date,
                'message_time' => $message_time,
                'user_id' => $user_id,
                'guest_id' => $guest_id
            );
            $this->messages_model->insertGuestMessage($data_guest_messages);
            $insert_message_id = $this->db->insert_id();

            $user_email = $this->users_model->getEmailById($guest_id);
            $user_name = $this->users_model->getNicknameAndSurnameById($guest_id);
            $user_image = $this->users_model->getMainImageById($guest_id);

            $insert_json = array(
                'message_id' => $insert_message_id,
                'message_date' => $message_date,
                'message_time' => $message_time,
                'message_text' => $message_text,
                'guest_id' => $guest_id,
                'user_email' => $user_email,
                'user_name' => $user_name,
                'user_image' => $user_image,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'message_error' => "Вы ввели пустой коммент или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_guest_message() {
        $id = $this->input->post('id');
        $guest_id = $this->input->post('guest_id');
        $message_num_rows = $this->messages_model->getGuestMessageNumRowsByIdAndGuestId($id, $guest_id);
        if ($message_num_rows > 0) {
            $this->messages_model->deleteGuestMessageById($id);
            $delete_json = array(
                'message_success' => "Запись удалена успешно",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'message_error' => "Не удалось удалить запись в гостевой или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}