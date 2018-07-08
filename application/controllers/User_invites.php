<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_invites extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_invite', $data);
    }

    public function insert_user_invite() {
        $invite_date = date('d.m.Y');
        $invite_time = date('H:i:s');

        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');

        $data_user_invites = array(
            'invite_date' => $invite_date,
            'invite_time' => $invite_time,
            'user_id' => $user_id,
            'invited_user_id' => $invited_user_id
        );
        $this->users_model->insertUserInvite($data_user_invites);
    }

    public function delete_user_invite_by_user_id() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');

        $invite_date = date('d.m.Y');
        $invite_time = date("H:i:s");
        $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($user_id, $invited_user_id);

        $notification_text = 'Пользователь Назар отказался принимать Вас в друзья.';
        $data_user_notifications = array(
            'notification_type' => 'Отказ от дружбы',
            'notification_text' => $notification_text,
            'notification_date' => $invite_date,
            'notification_time' => $invite_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_user_invite_by_invited_user_id() {
        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');
        $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($user_id, $invited_user_id);
        $delete_json = array(
            'invited_user_id' => $invited_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}