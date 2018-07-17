<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stake_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $stake_id = 3;
        $data = array(
            'stake_fans' => $this->stakes_model->getStakeFansByStakeId($stake_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('stake_fans', $data);
    }

    public function insert_stake_fan() {
        $stake_date = date('d.m.Y');
        $stake_time = date('H:i:s');
        $fan_user_id = $_SESSION['user_id'];
        $stake_id = $this->input->post('stake_id');

        $user_currency = $this->users_model->getCurrencyById($fan_user_id);
        $stake_price = $this->stakes_model->getStakePriceById($stake_id);

        if ($user_currency - $stake_price < 0) {
            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'stake_fan_error' => 'У Вас на счету ' . $user_currency . ' сомов, а подарок стоит ' . $stake_price . ', поэтому Вы не сможете заказать награду.'
            );
        } else {
            $data_stake_fans = array(
                'stake_date' => $stake_date,
                'stake_time' => $stake_time,
                'fan_user_id' => $fan_user_id,
                'stake_id' => $stake_id
            );
            $this->stakes_model->insertStakeFan($data_stake_fans);
            $data_users = array(
                'currency' => $user_currency - $stake_price
            );
            $this->users_model->updateUserById($fan_user_id, $data_users);

            $after_user_currency = $this->users_model->getCurrencyById($fan_user_id);

            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'stake_fan_success' => 'Вы успешно заказали награду. На Вашем счету осталось ' . $after_user_currency . " сомов."
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_stake_fan() {
        $this->load->view('session_user');
        $id = $this->input->post('id');
        $fan_user_id = $_SESSION['user_id'];
        $stake_fan_num_rows = $this->stakes_model->getStakeFanNumRowsByIdAndFanUserId($id, $fan_user_id);
        if ($stake_fan_num_rows > 0) {
            $this->stakes_model->deleteStakeFanById($id);
            $delete_json = array(
                'stake_fan_success' => 'Ваша награда успешно удалена',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'stake_fan_error' => "Это не Ваша награда или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}