<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('messages_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $this->load->view('session_user');

        $data = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('feedback_messages', $data);
    }

    public function insert_feedback_message() {
        $message_text = $this->input->post('message_text');
        $message_date = date("d.m.Y");
        $message_time = date("H:i:s");
        $admin_id = $this->admins_model->getRandomSuperAdminId();
        $user_id = $this->input->post('user_id');

        if ($message_text != '') {
            $data_feedback_messages = array(
                'message_text' => $message_text,
                'message_date' => $message_date,
                'message_time' => $message_time,
                'admin_id' => $admin_id,
                'user_id' => $user_id
            );
            $this->messages_model->insertFeedbackMessage($data_feedback_messages);

            $insert_message_id = $this->db->insert_id();

            $insert_json = array(
                'insert_message_id' => $insert_message_id,
                'message_success' => "Ваше сообщение отправлено! Спасибо!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'insert_message_id' => 0,
                'message_error' => "Вы ввели пустое сообщение!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_feedback_message() {
        $id = $this->input->post('id');
        $this->messages_model->deleteFeedbackMessage($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_all_feedback_messages() {
        $this->messages_model->deleteAllFeedbackMessages();
        $delete_json = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}