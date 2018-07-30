<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('admins_model');
        $this->load->model('users_model');
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
                    <td>Жалобщик</td>
                    <td>Текст жалобы</td>
                    <td>Проверка события</td>
                    <td>Удалить жалобу</td>
                    <td>Принять жалобу</td>
                </tr>";

            $event_complaints = $this->events_model->getEventComplaintsByAdminId($admin_id);

            foreach ($event_complaints as $event_complaint) {
                $id = $event_complaint->id;
                $email = $event_complaint->email;
                $complaint_text = $event_complaint->complaint_text;
                $event_id = $event_complaint->event_id;
                $event_name = $event_complaint->event_name;
                $html .= "<tr class='one-complaint-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>$complaint_text</td>
                        <td>
                            <button onclick='getOneEventByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneEvent' data-id='$event_id'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressEventComplaint(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteEventComplaint' data-complaint_id='$id' data-complaint_text='$complaint_text' data-event_name='$event_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressEventComplaintAndDeletePressEvent(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteEventComplaintAndEvent' data-complaint_id='$id' data-event_id='$event_id' data-complaint_text='$complaint_text' data-event_name='$event_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'event_complaints' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'complaint_error' => 'У вас нет прав на просмотр жалоб на события',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
    }


    public function insert_event_complaint() {
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('events');
        $event_id = $this->input->post('event_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->events_model->getEventComplaintNumRowsByEventIdAndComplainedUserId($event_id, $complained_user_id);
        if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
            $data_book_complaints = array(
                'complaint_text' => $complaint_text,
                'complaint_time_unix' => $complaint_time_unix,
                'admin_id' => $admin_id,
                'event_id' => $event_id,
                'complained_user_id' => $complained_user_id
            );
            $this->events_model->insertEventComplaint($data_book_complaints);
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                'event_id' => $event_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную книгу, или текст жалобы пуст, или что-то пошло не так.",
                'event_id' => $event_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_event_complaint() {
        $id = $this->input->post('id');
        $event_name = $this->input->post('event_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->events_model->deleteEventComplaintById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил жалобу на событие '$event_name' с текстом $complaint_text под id $id",
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

    public function delete_event_complaint_and_event() {
        $id = $this->input->post('id');
        $event_id = $this->input->post('event_id');
        $event_name = $this->input->post('event_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->events_model->deleteEventComplaintsByEventId($event_id);

            $this->events_model->deleteEventActionsByEventId($event_id);
            $this->events_model->deleteEventCommentsByEventId($event_id);
            $this->events_model->deleteEventComplaintsByEventId($event_id);
            $this->events_model->deleteEventEmotionsByEventId($event_id);
            $this->events_model->deleteEventFansByEventId($event_id);
            $this->events_model->deleteEventById($event_id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email принял жалобу на событие '$event_name' с текстом $complaint_text под id $id и удалил это событие под id $event_id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'complaint_success' => 'Жалоба успешно принята',
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