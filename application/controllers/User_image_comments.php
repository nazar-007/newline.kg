<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_image_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $user_image_id = 2;
        $data = array(
            'user_image_comments' => $this->users_model->getUserImageCommentsByUserImageId($user_image_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user_image_comments', $data);
    }

    public function insert_user_image_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date('d.m.Y');
        $comment_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $user_image_id = $this->input->post('user_image_id');

        $data_user_image_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'user_id' => $user_id,
            'commented_user_id' => $commented_user_id,
            'user_image_id' => $user_image_id,
        );
        $this->users_model->insertUserImageComment($data_user_image_comments);

        if ($user_id != $commented_user_id) {
            $notification_text = 'Пользователь Назар оставил коммент на Вашу фотку';
            $data_user_notifications = array(
                'notification_type' => 'Коммент на Вашу фотку',
                'notification_text' => $notification_text,
                'notification_date' => $comment_date,
                'notification_time' => $comment_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);
        }

    }

    public function delete_user_image_comment() {
        $id = $this->input->post('id');
        $this->users_model->deleteUserImageCommentById($id);
        $this->users_model->deleteUserImageCommentEmotionsByUserImageCommentId($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}