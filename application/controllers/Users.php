<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('albums_model');
        $this->load->model('books_model');
        $this->load->model('countries_model');
        $this->load->model('documents_model');
        $this->load->model('events_model');
        $this->load->model('folders_model');
        $this->load->model('gifts_model');
        $this->load->model('messages_model');
        $this->load->model('publications_model');
        $this->load->model('songs_model');
        $this->load->model('stakes_model');
        $this->load->model('users_model');
        $this->load->library('session');
    }
    public function Index() {
        if ($_SESSION['user_id'] && $_SESSION['user_email']) {
            redirect('/publications');
        }
        $data = array(
            'countries' => $this->countries_model->getCountries(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('index', $data);
    }
    public function Authorization() {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        $user_id = $this->users_model->getUserIdByEmailAndPassword($email, $password);
        $num_rows = $this->users_model->getNumRowsByEmailAndPassword($email, $password);
        if($num_rows > 0) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            redirect('/publications');
        } else {
            redirect('/');
        }
    }

    public function One_user($email) {
        $user_num_rows = $this->users_model->getUserNumRowsByEmail($email);
        if ($user_num_rows == 1) {
            $data_users = array(
                'one_user' => $this->users_model->getOneUserByEmail($email),
                'user_num_rows' => $user_num_rows
            );
        } else {
            echo "Страница удалена или ещё не создана!";
            $data_users = array(
                'user_num_rows' => $user_num_rows
            );
        }
        $this->load->view('one_user', $data_users);
    }

    public function Logout() {
        session_start();
        session_destroy();
        redirect('/');
    }

    public function Online() {
        $id = $this->input->post('id');
        $data = array(
            'last_visit' => 'Онлайн'
        );
        $json = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->users_model->updateUserById($id, $data);
        echo json_encode($json);
    }

    public function Last_visit() {
        $id = $this->input->post('id');
        $data = array(
            'last_visit' => 'заходил(а) в ' . date('d.m.Y') . ' ' . date("H:i:s")
        );
        $json = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->users_model->updateUserById($id, $data);
        echo json_encode($json);
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
        $date_mk = date('d.m.Y');
        $time_mk = date('H:i:s');
        $home_land = $this->input->post('home_land');
        $schools = $this->input->post('education_schools');
        $universities = $this->input->post('education_universities');
        $family_position = $this->input->post('family_position');
        $num_rows = $this->users_model->getNumRowsByEmail($email);

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($num_rows > 0 || strlen($email) < 5 || empty($email) || $password !== $check_password || empty($password)
            || strlen($password) < 6 || empty($nickname) || strlen($nickname) < 2 || empty($surname)
            || strlen($surname) < 2 || empty($day) || empty($month) || empty($birth_year)
            || $birth_date == '30 февраля' || $birth_date == '31 февраля' || $birth_date == '31 апреля'
            || $birth_date == '31 июня' || $birth_date == '31 сентября' || $birth_date == '31 ноября'
            || ($birth_date == '29 февраля' && $birth_year % 4 != 0) || empty($gender)
            || empty($secret_question_1) || empty($secret_answer_1) || empty($secret_question_2)
            || empty($secret_answer_2)) {


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
        } else {
            $config['upload_path'] = './uploads/images/user_images/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['file_name'] = time();
            $config['max_size'] = 1000;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $upload_image = $this->upload->data();
                $main_image = $upload_image['file_name'];
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
                $education_schools = "Не указано";
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
                $education_universities = "Не указано";
            }

            $education_schools = $education_schools == '' ? "Не указано" : $education_schools;
            $education_universities = $education_universities == '' ? "Не указано" : $education_universities;

            $data_users = array(
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
                'sign_date' => $date_mk,
                'sign_time' => $time_mk,
                'my_account_access' => "Открыто",
                'my_page_access' => "Открыто"
            );
            $this->users_model->insertUser($data_users);
            $insert_user_id = $this->db->insert_id();

            $data_user_albums = array(
                'album_name' => "User Album",
                'user_id' => $insert_user_id
            );
            $this->albums_model->insertAlbum($data_user_albums);

            $data_publication_albums = array(
                'album_name' => "Publication Album",
                'user_id' => $insert_user_id
            );
            $this->albums_model->insertAlbum($data_publication_albums);

            $data_folders = array(
                'folder_name' => "User Folder",
                'user_id' => $insert_user_id
            );
            $this->folders_model->insertFolder($data_folders);

            $_SESSION['user_id'] = $insert_user_id;
            $_SESSION['user_email'] = $email;
            $messages['success_registration'] = "Регистрация прошла успешно!";
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }

    public function delete_user() {
        $id = $this->input->post('id');
        $book_suggestions = $this->books_model->getBookSuggestionsBySuggestedUserId($id);
        foreach ($book_suggestions as $book_suggestion) {
            $book_suggestion_file = $book_suggestion->suggestion_file;
            $book_suggestion_image = $book_suggestion->suggestion_image;
            unlink("./uploads/book_files/$book_suggestion_file");
            unlink("./uploads/images/book_images/$book_suggestion_image");
            unlink("./uploads/images/book_images/thumb/$book_suggestion_image");
        }
        $song_suggestions = $this->songs_model->getSongSuggestionsBySuggestedUserId($id);
        foreach ($song_suggestions as $song_suggestion) {
            $song_suggestion_file = $song_suggestion->suggestion_file;
            $song_suggestion_image = $song_suggestion->suggestion_image;
            unlink("./uploads/song_files/$song_suggestion_file");
            unlink("./uploads/images/song_images/$song_suggestion_image");
            unlink("./uploads/images/song_images/thumb/$song_suggestion_image");
        }
        $publications = $this->publications_model->getPublicationsByPublishedUserId($id);
        foreach ($publications as $publication) {
            $publication_id = $publication->id;
            $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
            foreach ($publication_images as $publication_image) {
                $publication_image_id = $publication_image->id;
                $publication_image_file = $this->publications_model->getPublicationImageFileById($publication_image_id);
                unlink("./uploads/images/publication_images/$publication_image_file");
                unlink("./uploads/images/publication_images/thumb/$publication_image_file");
                $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($publication_image_id);
            }
            $publication_shares = $this->publications_model->getPublicationSharesByPublicationId($publication_id);
            foreach ($publication_shares as $publication_share) {
                $publication_share_id = $publication_share->id;
                $this->publications_model->deletePublicationShareEmotionsByPublicationShareId($publication_share_id);
            }
            $this->publications_model->deletePublicationCommentsByPublicationId($publication_id);
            $this->publications_model->deletePublicationComplaintsByPublicationId($publication_id);
            $this->publications_model->deletePublicationEmotionsByPublicationId($publication_id);
            $this->publications_model->deletePublicationImagesByPublicationId($publication_id);
            $this->publications_model->deletePublicationSharesByPublicationId($publication_id);
        }
        $user_images = $this->users_model->getUserImagesByUserId($id);
        foreach ($user_images as $user_image) {
            $user_image_id = $user_image->id;
            $user_image_file = $this->users_model->getUserImageFileById($user_image_id);
            unlink("./uploads/images/user_images/$user_image_file");
            unlink("./uploads/images/user_images/thumb/$user_image_file");
            $this->users_model->deleteUserImageActionsByUserImageId($user_image_id);
            $this->users_model->deleteUserImageCommentsByUserImageId($user_image_id);
            $this->users_model->deleteUserImageCommentEmotionsByUserImageId($user_image_id);
            $this->users_model->deleteUserImageEmotionsByUserImageId($user_image_id);
        }
        $this->albums_model->deleteAlbumsByUserId($id);
        $this->books_model->deleteBookActionsByActionUserId($id);
        $this->books_model->deleteBookCommentsByCommentedUserId($id);
        $this->books_model->deleteBookComplaintsByComplainedUserId($id);
        $this->books_model->deleteBookEmotionsByEmotionedUserId($id);
        $this->books_model->deleteBookFansByFanUserId($id);
        $this->books_model->deleteBookSuggestionsBySuggestedUserId($id);
        $this->documents_model->deleteDocumentsByUserId($id);
        $this->events_model->deleteEventActionsByActionUserId($id);
        $this->events_model->deleteEventCommentsByCommentedUserId($id);
        $this->events_model->deleteEventComplaintsByComplainedUserId($id);
        $this->events_model->deleteEventEmotionsByEmotionedUserId($id);
        $this->events_model->deleteEventFansByFanUserId($id);
        $this->events_model->deleteEventSuggestionsBySuggestedUserId($id);
        $this->messages_model->deleteFeedbackMessagesByUserId($id);
        $this->folders_model->deleteFoldersByUserId($id);
        $this->users_model->deleteFriendsByUserIdOrFriendId($id);
        $this->gifts_model->deleteGiftSentByUserIdOrSentUserId($id);
        $this->messages_model->deleteGuestMessagesByUserIdOrGuestId($id);
        $this->users_model->deleteGuestsByUserIdOrGuestId($id);
        $this->messages_model->deletePrivateMessagesByUserIdOrTalkerId($id);
        $this->publications_model->deletePublicationsByPublishedUserId($id);
        $this->publications_model->deletePublicationCommentsByCommentedUserId($id);
        $this->publications_model->deletePublicationComplaintsByPublishedUserIdOrComplainedUserId($id);
        $this->publications_model->deletePublicationEmotionsByPublishedUserIdOrEmotionedUserId($id);
        $this->publications_model->deletePublicationImageEmotionsByPublishedUserIdOrEmotionedUserId($id);
        $this->publications_model->deletePublicationSharesBySharedUserId($id);
        $this->publications_model->deletePublicationShareEmotionsBySharedUserIdOrEmotionedUserId($id);
        $this->songs_model->deleteSongActionsByActionUserId($id);
        $this->songs_model->deleteSongCommentsByCommentedUserId($id);
        $this->songs_model->deleteSongComplaintsByComplainedUserId($id);
        $this->songs_model->deleteSongEmotionsByEmotionedUserId($id);
        $this->songs_model->deleteSongFansByFanUserId($id);
        $this->songs_model->deleteSongSuggestionsBySuggestedUserId($id);
        $this->stakes_model->deleteStakeFansByFanUserId($id);
        $this->users_model->deleteUserBlacklistByUserIdOrBlackUserId($id);
        $this->users_model->deleteUserComplaintsByUserIdOrComplainedUserId($id);
        $this->users_model->deleteUserImageActionsByUserIdOrActionUserId($id);
        $this->users_model->deleteUserImageCommentsByUserIdOrCommentedUserId($id);
        $this->users_model->deleteUserImageEmotionsByUserIdOrEmotionedUserId($id);
        $this->users_model->deleteUserImagesByUserId($id);
        $this->users_model->deleteUserInvitesByUserIdOrInvitedUserId($id);
        $this->users_model->deleteUserNotificationsByUserId($id);
        $this->users_model->deleteUserPageEmotionsByUserIdOrEmotionedUserId($id);
        $this->users_model->deleteUserById($id);

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_user() {
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $nickname = $this->input->post('nickname');
        $surname = $this->input->post('surname');
        $day = $this->input->post('day');
        $month = $this->input->post('month');
        $birth_date = $day . " " . $month;
        $birth_year = $this->input->post('birth_year');
        $gender = $this->input->post('gender');
        $home_land = $this->input->post('home_land');
        $schools = $this->input->post('education_schools');
        $universities = $this->input->post('education_universities');
        $family_position = $this->input->post('family_position');
        $num_rows = $this->users_model->getNumRowsByEmail($email);

        $current_home_land = $this->users_model->getHomeLandById($id);
        $current_family_position = $this->users_model->getFamilyPositionById($id);
        $current_education_schools = $this->users_model->getEducationSchoolsById($id);
        $current_education_universities = $this->users_model->getEducationUniversitiesById($id);

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($num_rows > 0 || strlen($email) < 5 || empty($email) || empty($nickname) || strlen($nickname) < 2 || empty($surname)
            || strlen($surname) < 2 || empty($day) || empty($month) || empty($birth_year)
            || $birth_date == '30 февраля' || $birth_date == '31 февраля' || $birth_date == '31 апреля'
            || $birth_date == '31 июня' || $birth_date == '31 сентября' || $birth_date == '31 ноября'
            || ($birth_date == '29 февраля' && $birth_year % 4 != 0)) {


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
        } else {
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
                $education_schools = "Не указано";
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
                $education_universities = "Не указано";
            }

            $home_land = $home_land == '' ? $current_home_land : $home_land;
            $family_position = $family_position == '' ? $current_family_position : $family_position;
            $education_schools = $education_schools == '' ? $current_education_schools : $education_schools;
            $education_universities = $education_universities == '' ? $current_education_universities : $education_universities;

            $data_users = array(
                'email' => $email,
                'nickname' => $nickname,
                'surname' => $surname,
                'birth_date' => $birth_date,
                'birth_year' => $birth_year,
                'gender' => $gender,
                'home_land' => $home_land,
                'education_schools' => $education_schools,
                'education_universities' => $education_universities,
                'family_position' => $family_position,
                'my_page_access' => "Открыто"
            );
            $this->users_model->updateUserById($id, $data_users);
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }
    public function update_user_password() {
        $id = $this->input->post('id');
        $db_password = $this->users_model->getPasswordById($id);
        $current_password = md5($_POST['current_password']);
        $new_password = $this->input->post('new_password');
        $check_new_password = $this->input->post('check_new_password');

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($db_password != $current_password || $new_password != $check_new_password || empty($new_password)
            || strlen($new_password) < 6) {

            if ($db_password != $current_password) {
                $messages['password_current_incorrect'] = 'Текущий пароль неверный!';
            }

            if ($new_password != $check_new_password) {
                $messages['password_new_mismatch'] = 'Новые пароли не совпадают!';
            }
            if (empty($new_password)) {
                $messages['password_new_empty'] = 'Новый пароль пуст!';
            }
            if (strlen($new_password) < 6) {
                $messages['password_new_less'] = 'Новый пароль слишком короткий, введите не менее 6 символов!';
            }
        } else {
            $data_users = array(
                'password' => md5($new_password),
            );
            $this->users_model->updateUserById($id, $data_users);
            $messages['success_new_password'] = "Пароль успешно изменён!";
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }
    public function update_user_secret_questions_and_answers() {
        $id = $this->input->post('id');
        $new_secret_question_1 = $this->input->post('new_secret_question_1');
        $new_secret_answer_1 = $this->input->post('new_secret_answer_1');
        $new_secret_question_2 = $this->input->post('new_secret_question_2');
        $new_secret_answer_2 = $this->input->post('new_secret_answer_2');

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if (empty($new_secret_question_1) || empty($new_secret_answer_1) || empty($new_secret_question_2) || empty($new_secret_answer_2)) {
            if (empty($new_secret_question_1)) {
                $messages['secret_question_1_new_empty'] = 'Новый секретный вопрос 1 не выбран, выберите из списка!';
            }
            if (empty($new_secret_answer_1)) {
                $messages['secret_answer_1_new_empty'] = 'Ответ на новый 1 секретный вопрос пуст, введите свой ответ!';
            }
            if (empty($new_secret_question_2)) {
                $messages['secret_question_2_new_empty'] = 'Новый секретный вопрос 2 не выбран, выберите из списка!';
            }
            if (empty($new_secret_answer_2)) {
                $messages['secret_answer_2_new_empty'] = 'Ответ на новый 2 секретный вопрос пуст, введите свой ответ!';
            }
        } else {
            $data_users = array(
                'secret_question_1' => $new_secret_question_1,
                'secret_answer_1' => $new_secret_answer_1,
                'secret_question_2' => $new_secret_question_2,
                'secret_answer_2' => $new_secret_answer_2,
            );
            $this->users_model->updateUserById($id, $data_users);
            $messages['success_secret_questions_and_answers'] = "Секретные вопросы и ответы изменены!";
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }
}