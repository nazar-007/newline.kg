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
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('users');
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

    public function get_feedback_messages_by_admin() {
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        $html = '';
        if ($admin_id && $admin_email && $admin_table) {
            $html .= "<h3 class='centered'>Вы - админ пользователей.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>От кого</td>
                    <td>Текст письма</td>
                    <td>Просмотр юзера</td>
                    <td>Удалить письмо</td>
                </tr>";

            $feedback_messages = $this->messages_model->getFeedbackMessagesByAdminId($admin_id);

            foreach ($feedback_messages as $feedback_message) {
                $id = $feedback_message->id;
                $email = $feedback_message->email;
                $user_id = $feedback_message->user_id;
                $message_text = $feedback_message->message_text;
                $html .= "<tr class='one-message-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>$message_text</td> 
                        <td>
                            <button onclick='getOneUserByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneUser' data-id='$user_id'><span class='glyphicon glyphicon-align-justify'></span></button>        
                        </td>
                        <td>
                            <button onclick='deletePressFeedbackMessage(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteFeedbackMessage' data-id='$id' data-message_text='$message_text'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'feedback_messages' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'message_error' => 'У вас нет прав на просмотр жалоб на книги',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
    }

    public function delete_feedback_message() {
        $id = $this->input->post('id');
        $message_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->messages_model->deleteFeedbackMessageById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email удалил сообщение '$message_text' под id $id",
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
                'complaint_error' => "Не удалось удалить жалобу.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}