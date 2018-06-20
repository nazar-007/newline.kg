<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEvents($category_ids),
            'event_categories' => $this->events_model->getEventCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('events', $data);
    }

    public function choose_event_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $events = $this->events_model->getEvents($category_ids);
        foreach ($events as $event) {
            echo "<tr>
            <td>$event->id</td>
            <td>
                <a href='" . base_url() . "models/" . $event->id . "'>" . $event->event_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_user() {

        $email = $this->input->post('email');
        $password = $_POST['password'];
        $check_password = $_POST['check_password'];
        $nickname = $this->input->post('nickname');
        $surname = $this->input->post('surname');
        $day = $this->input->post('day');
        $month = $this->input->post('month');
        $birth_date = $day . " " . $month;
        $birth_year = $this->input->post('birth_year');
        $gender = $this->input->post('gender');
        $secret_question_1 = $this->input->post('secret_question_1');
        $secret_answer_1 = $this->input->post('secret_answer_1');
        $secret_question_2 = $this->input->post('secret_question_2');
        $secret_answer_2 = $this->input->post('secret_answer_2');

        $home_land = $this->input->post('home_land');
        $schools = $this->input->post('education_schools');
        $universities = $this->input->post('education_universities');
        $family_position = $this->input->post('family_position');

        $num_rows = $this->users_model->getNumRowsByEmail($email);

        if ($num_rows > 0 || strlen($email) < 5 || empty($email) || $password !== $check_password || empty($password)
            || strlen($password) < 6 || empty($nickname) || strlen($nickname) < 2 || empty($surname)
            || strlen($surname) < 2 || empty($day) || empty($month) || empty($birth_year)
            || $birth_date == '30 февраля' || $birth_date == '31 февраля' || $birth_date == '31 апреля'
            || $birth_date == '31 июня' || $birth_date == '31 сентября' || $birth_date == '31 ноября'
            || ($birth_date == '29 февраля' && $birth_year % 4 != 0) || empty($gender)
            || empty($secret_question_1) || empty($secret_answer_1) || empty($secret_question_2)
            || empty($secret_answer_2)) {

            $messages = array(
                'csrf_hash' => $this->security->get_csrf_hash()
            );

            if ($num_rows > 0) {
                $messages['email_num_rows'] = $num_rows;
                $messages['email_exist'] = 'Такой email уже существует! Придумайте другой!';
            }
            if (empty($email)) {
                $messages['email_empty'] = 'Email пустой, введите корректный логин';
            }
            if (strlen($email) < 5) {
                $messages['email_less'] = 'Email слишком короткий, введите не менее 5 символов';
            }
            if ($password !== $check_password) {
                $messages['password_mismatch'] = 'Пароли не совпадают!';
            }
            if (empty($password)) {
                $messages['password_empty'] = 'Пустой пароль';
            }
            if (strlen($password) < 6) {
                $messages['password_less'] = 'Слишком короткий пароль, введите не менее 6 символов!';
            }
            if (empty($nickname)) {
                $messages['nickname_empty'] = 'Имя пустое, введите корректное имя!';
            }
            if (strlen($nickname) < 2) {
                $messages['nickname_less'] = 'Имя слишком короткое, введите не менее 2 символов!';
            }
            if (empty($surname)) {
                $messages['surname_empty'] = 'Фамилия пустая, введите корректную фамилию!';
            }
            if (strlen($surname) < 2) {
                $messages['surname_less'] = 'Фамилия слишком короткая, введите не менее 2 символов!';
            }
            if ($day == '' || $month == '' || $birth_year == '') {
                $messages['birth_date_empty'] = 'Введите корректную дату рождения!';
            }
            if ($birth_date == '30 февраля' || $birth_date == '31 февраля' || $birth_date == '31 апреля' || $birth_date == '31 июня' || $birth_date == '31 сентября' || $birth_date == '31 ноября' || ($birth_date == '29 февраля' && $birth_year % 4 != 0)) {
                $messages['birth_date_incorrect'] = 'Введенные данные не совпадают с реальностью! Введите корректную дату!';
            }
            if (empty($gender)) {
                $messages['gender_empty'] = 'Пол не указан, введите свой пол!';
            }
            if (empty($secret_question_1)) {
                $messages['secret_question_1_empty'] = 'Секретный вопрос 1 не выбран, выберите из списка!';
            }
            if (empty($secret_answer_1)) {
                $messages['secret_answer_1_empty'] = 'Ответ на 1 вопрос пуст, введите свой ответ!';
            }
            if (empty($secret_question_2)) {
                $messages['secret_question_2_empty'] = 'Секретный вопрос 2 не выбран, выберите из списка!';
            }
            if (empty($secret_answer_2)) {
                $messages['secret_answer_2_empty'] = 'Ответ на 2 вопрос пуст, введите свой ответ!';
            }
            $messages_json = json_encode($messages);
            echo $messages_json;
        } else {
            $config['upload_path'] = './uploads/images/user_images/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['file_name'] = time();
            $config['max_size'] = 1000;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $data_image = $this->upload->data();
                $main_image = $data_image['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = "./uploads/images/user_images/$main_image";
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 128;
                $config['height'] = 128;
                $config['quality'] = '100%';
                $config['new_image'] = "./uploads/images/user_images/thumb/$main_image";

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
            } else {
                $main_image = "default.jpg";
            }

            $home_land = $home_land == '' ? "Не указано" : $home_land;
            $family_position = $family_position == '' ? "Не указано" : $family_position;

            if (count($schools) > 0) {
                $education_schools = '';
                for ($i = 0; $i < count($schools); $i++) {
                    $last_index = count($schools) - 1;
                    if ($schools[$i] != '') {
                        if ($i != $last_index) {
                            $education_schools .= $schools[$i] . ", ";
                        } else {
                            $education_schools .= $schools[$i];
                        }
                    }
                }
            } else {
                $education_schools = "Не указано!";
            }

            if (count($universities) > 0) {
                $education_universities = '';
                for ($i = 0; $i < count($universities); $i++) {
                    $last_index = count($universities) - 1;
                    if ($universities[$i] != '') {
                        if ($i != $last_index) {
                            $education_universities .= $universities[$i] . ", ";
                        } else {
                            $education_universities .= $universities[$i];
                        }
                    }
                }
            } else {
                $education_universities = "Не указано!";
            }

            $data = array(
                'email' => $email,
                'password' => md5($password),
                'nickname' => $nickname,
                'surname' => $surname,
                'birth_date' => $birth_date,
                'birth_year' => $birth_year,
                'gender' => $gender,
                'secret_question_1' => $secret_question_1,
                'secret_answer_1' => $secret_answer_1,
                'secret_question_2' => $secret_question_2,
                'secret_answer_2' => $secret_answer_2,
                'main_image' => $main_image,
                'home_land' => $home_land,
                'education_schools' => $education_schools,
                'education_universities' => $education_universities,
                'family_position' => $family_position,
                'last_visit' => 'На линии',
                'currency' => 0,
                'rating' => 0,
                'rank' => "Новичок",
                'sign_date' => date('d.m.Y'),
                'sign_time' => date('H:i:s'),
                'my_account_access' => "Открыто",
                'my_page_access' => "Открыто"
            );
            $this->events_model->insertEvent($data);
        }
    }
}