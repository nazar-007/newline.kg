<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admins_model');
    }
    public function Index() {
        $data = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('admins', $data);
    }

    public function insert_admin()
    {
        $admin_email = $this->input->post('admin_email');
        $admin_password = $_POST['admin_password'];
        $admin_check_password = $_POST['admin_check_password'];
        $admin_nickname = $this->input->post('admin_nickname');
        $admin_type = $this->input->post('admin_type');
        $admin_table = $this->input->post('admin_table');
        $admin_image = $this->input->post('admin_image');

        $num_rows = $this->admins_model->getNumRowsByEmail($admin_email);
        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($num_rows > 0 || strlen($admin_email) < 5 || empty($admin_email) || $admin_password !== $admin_check_password || empty($admin_password)
            || strlen($admin_password) < 6 || empty($admin_nickname) || strlen($admin_nickname) < 2 || empty($admin_type)
            || empty($admin_table)) {
            if ($num_rows > 0) {
                $messages['email_num_rows'] = $num_rows;
                $messages['email_exist'] = 'Такой email уже существует! Придумайте другой!';
            }
            if (empty($admin_email)) {
                $messages['email_empty'] = 'Email пустой, введите корректный логин';
            }
            if (strlen($admin_email) < 5) {
                $messages['email_less'] = 'Email слишком короткий, введите не менее 5 символов';
            }
            if ($admin_password !== $admin_check_password) {
                $messages['password_mismatch'] = 'Пароли не совпадают!';
            }
            if (empty($admin_password)) {
                $messages['password_empty'] = 'Пустой пароль';
            }
            if (strlen($admin_password) < 6) {
                $messages['password_less'] = 'Слишком короткий пароль, введите не менее 6 символов!';
            }
            if (empty($admin_nickname)) {
                $messages['nickname_empty'] = 'Имя пустое, введите корректное имя!';
            }
            if (strlen($admin_nickname) < 2) {
                $messages['nickname_less'] = 'Имя слишком короткое, введите не менее 2 символов!';
            }
            if (empty($admin_type)) {
                $messages['type_empty'] = 'Тип не выбран, выберите тип!';
            }
            if (empty($admin_table)) {
                $messages['table_empty'] = 'Таблица не выбрана, выберите таблицу!';
            }
        } else {
            $admin_image = $admin_image == '' ? "default.jpg" : $admin_image;
            $data_admins = array (
                'admin_email' => $admin_email,
                'admin_password' => md5($admin_password),
                'admin_nickname' => $admin_nickname,
                'admin_type' => $admin_type,
                'admin_table' => $admin_table,
                'admin_image' => $admin_image,
                'admin_rating' => 0,
                'admin_rank' => "Новичок",
                'admin_sign_date' => date('d.m.Y'),
                'admin_sign_time' => date('H:i:s'),
                'admin_access' => 'Открыто'
            );
            $this->admins_model->insertAdmin($data_admins);
        }
    }
}