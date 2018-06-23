<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_sent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'gifts' => $this->gifts_model->getGifts($category_ids),
            'gift_categories' => $this->gifts_model->getGiftCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('gift_sent', $data);
    }

    public function insert_gift_sent() {
        $sent_date = date('d.m.Y');
        $sent_time = date('H:i:s');
        $sent_user_id = $this->input->post('gifted_user_id');
        $user_id = $this->input->post('user_id');
        $gift_id = $this->input->post('gift_id');

        $data_gift_sent = array(
            'sent_date' => $sent_date,
            'sent_time' => $sent_time,
            'sent_user_id' => $sent_user_id,
            'user_id' => $user_id,
            'gift_id' => $gift_id
        );
        $this->gifts_model->insertGiftSent($data_gift_sent);

        $notification_text = 'Пользователь Назар сделал Вам подарок';

        $data_user_notifications = array(
            'notification_type' => 'Подарок для Вас',
            'notification_text' => $notification_text,
            'notification_date' => $sent_date,
            'notification_time' => $sent_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_gift_sent() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftSentById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}