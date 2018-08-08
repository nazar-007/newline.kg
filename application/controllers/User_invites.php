<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_invites extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function insert_user_invite() {
        $invite_date = date('d.m.Y');
        $invite_time = date('H:i:s');

        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');

        $invite_num_rows = $this->users_model->getUserInviteNumRowsByUserIdAndInvitedUserId($user_id, $invited_user_id);
        $friend_num_rows = $this->users_model->getFriendNumRowsByUserIdAndFriendId($user_id, $invited_user_id);

        if ($invite_num_rows > 0 || $user_id == $invited_user_id || $friend_num_rows > 0) {
            $insert_json = array(
                'invite_error' => "Не удалось отправить дружбу",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_user_invites = array(
                'invite_date' => $invite_date,
                'invite_time' => $invite_time,
                'user_id' => $user_id,
                'invited_user_id' => $invited_user_id
            );
            $this->users_model->insertUserInvite($data_user_invites);

            $insert_json = array(
                'invite_success' => "Предложение отправлено",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_user_invite_by_user_id() {
        $invite_date = date('d.m.Y');
        $invite_time = date('H:i:s');

        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');

        $user_name = $this->users_model->getNicknameAndSurnameById($user_id);
        $invited_user_name = $this->users_model->getNicknameAndSurnameById($invited_user_id);

        $user_invite_num_rows = $this->users_model->getUserInviteNumRowsByUserIdAndInvitedUserId($user_id, $invited_user_id);

        if ($user_invite_num_rows > 0) {
            $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($user_id, $invited_user_id);
            $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($invited_user_id, $user_id);

            $notification_text = "$user_name отказался принимать Вас в друзья.";
            $data_user_notifications = array(
                'notification_type' => 'Отказ от дружбы',
                'notification_text' => $notification_text,
                'notification_date' => $invite_date,
                'notification_time' => $invite_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => 0,
                'link_table' => 0,
                'user_id' => $invited_user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $delete_json = array(
                'invite_success' => "Предложение дружбы от $invited_user_name отклонено Вами",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'invite_error' => "Не удалось отклонить предложение",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_user_invite_by_invited_user_id() {
        $user_id = $this->input->post('user_id');
        $invited_user_id = $this->input->post('invited_user_id');

        $user_invite_num_rows = $this->users_model->getUserInviteNumRowsByUserIdAndInvitedUserId($user_id, $invited_user_id);

        if ($user_invite_num_rows > 0) {
            $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($user_id, $invited_user_id);
            $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($invited_user_id, $user_id);
            $delete_json = array(
                'invite_success' => "Предложение отменено успешно!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'invite_error' => "Не удалось отклонить предложение",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}