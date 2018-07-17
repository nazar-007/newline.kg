<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('messages_model');
    }
    public function Index() {
        $user_id = 1;
        $data = array(
            'guest_messages' => $this->messages_model->getGuests($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('guest_messages', $data);
    }

    public function insert_guest_message() {
        $message_text = $this->input->post('message_text');
        $message_date = date("d.m.Y");
        $message_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $guest_id = $this->input->post('guest_id');

        $data_guest_messages = array(
            'message_text' => $message_text,
            'message_date' => $message_date,
            'message_time' => $message_time,
            'user_id' => $user_id,
            'guest_id' => $guest_id
        );
        $this->messages_model->insertGuestMessage($data_guest_messages);
    }

    public function delete_guest_message() {
        $id = $this->input->post('id');
        $this->messages_model->deleteGuestMessage($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_all_guest_messages() {
        $user_id = $this->input->post('user_id');
        $this->messages_model->deleteAllGuestMessagesByUserId($user_id);
        $delete_json = array(
            'user_id' => $user_id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_guest_message() {
        $id = $this->input->post('id');
        $message_text = $this->input->post('message_text');

        $data_guest_messages = array(
            'message_text' => $message_text
        );
        $this->messages_model->updateGuestMessageById($id, $data_guest_messages);
    }
}