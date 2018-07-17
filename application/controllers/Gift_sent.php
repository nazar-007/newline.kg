<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_sent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
        $this->load->model('users_model');
    }

    public function Index() {
//        $category_ids = array();
//        $user_id = $_SESSION['user_id'];
//        $data_gift_sent = array(
//            'gifts' => $this->gifts_model->getGiftsByCategoryIds($category_ids),
//            'my_gifts' => $this->gifts_model->getGiftSentByUserId($user_id),
//            'friends' => $this->users_model->getFriendsByUserId($user_id),
//            'gift_categories' => $this->gifts_model->getGiftCategories(),
//            'csrf_hash' => $this->security->get_csrf_hash()
//        );
//        $this->load->view('gift_sent', $data_gift_sent);
    }

    public function insert_gift_sent() {
        $this->load->view('session_user');
        $sent_date = date('d.m.Y');
        $sent_time = date('H:i:s');
        $sent_user_id = $_SESSION['user_id'];
        $user_id = $this->input->post('user_id');
        $gift_id = $this->input->post('gift_id');

        $user_currency = $this->users_model->getCurrencyById($sent_user_id);
        $gift_price = $this->gifts_model->getGiftPriceById($gift_id);

        if ($user_id == '') {
            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'gift_sent_error' => 'Выберите пользователя'
            );
        } else if ($user_currency - $gift_price < 0) {
            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'gift_sent_error' => 'У Вас на счету ' . $user_currency . ' сомов, а подарок стоит ' . $gift_price . ', поэтому Вы не сможете отправить подарок.'
            );
        } else {
            $data_gift_sent = array(
                'sent_date' => $sent_date,
                'sent_time' => $sent_time,
                'gift_viewed' => 0,
                'sent_user_id' => $sent_user_id,
                'user_id' => $user_id,
                'gift_id' => $gift_id
            );
            $this->gifts_model->insertGiftSent($data_gift_sent);
            $data_users = array(
                'currency' => $user_currency - $gift_price
            );
            $this->users_model->updateUserById($sent_user_id, $data_users);

            $after_user_currency = $this->users_model->getCurrencyById($sent_user_id);

            $sent_user_name = $this->users_model->getNicknameAndSurnameById($sent_user_id);
            $notification_text = "$sent_user_name отправил Вам подарок";
            $data_user_notifications = array(
                'notification_type' => 'Подарок от пользователя',
                'notification_text' => $notification_text,
                'notification_date' => $sent_date,
                'notification_time' => $sent_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $user_name = $this->users_model->getNicknameAndSurnameById($user_id);

            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'gift_sent_success' => 'Подарок успешно отправлен пользователю ' . $user_name . '. На Вашем счету осталось ' . $after_user_currency . " сомов."
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_gift_sent() {
        $this->load->view('session_user');
        $id = $this->input->post('id');
        $user_id = $_SESSION['user_id'];
        $gift_sent_num_rows = $this->gifts_model->getGiftSentNumRowsByIdAndUserId($id, $user_id);
        if ($gift_sent_num_rows > 0) {
            $this->gifts_model->deleteGiftSentById($id);
            $delete_json = array(
                'gift_sent_success' => 'Подарок, подаренный Вам, удалён',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'gift_sent_error' => "Это не Ваш подарок или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}