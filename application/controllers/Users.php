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
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('index', $data);
    }
    public function Authorization() {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        $user_id = $this->users_model->getUserIdByEmailAndPassword($email, $password);
        $user_email = $this->users_model->getEmailById($user_id);
        $num_rows = $this->users_model->getNumRowsByEmailAndPassword($email, $password);
        if($num_rows > 0) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $user_email;
            redirect(base_url() . "publications");
        } else {
            redirect(base_url());
        }
    }

    public function My_page() {
        $this->load->view('session_user');
        $user_id = $_SESSION['user_id'];
        $users = $this->users_model->getUserById($user_id);
        $data = array(
            'users' => $users,
            'total_user_page_emotions' => $this->users_model->getTotalByUserIdAndUserTable($user_id, 'user_page_emotions'),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('my_page', $data);
    }

    public function One_user($email) {
        $this->load->view('session_user');
        $user_num_rows = $this->users_model->getUserNumRowsByEmail($email);
        if ($user_num_rows == 1 && $email == $_SESSION['user_email']) {
            redirect(base_url() . "my_page");
        } else if ($user_num_rows == 1) {
            $guest_date = date('d.m.Y');
            $guest_time = date('H:i:s');
            $guest_time_unix = time();
            $user_id = $this->users_model->getUserIdByEmail($email);
            $guest_id = $_SESSION['user_id'];
            $guest_num_rows = $this->users_model->getGuestNumRowsByUserIdAndGuestId($user_id, $guest_id);

            if ($guest_num_rows == 0) {
                $data_guests = array(
                    'guest_date' => $guest_date,
                    'guest_time' => $guest_time,
                    'guest_time_unix' => $guest_time_unix,
                    'user_viewed' => 0,
                    'user_id' => $user_id,
                    'guest_id' => $guest_id
                );
                $this->users_model->insertGuest($data_guests);
            } else {
                $data_guests = array(
                    'guest_date' => $guest_date,
                    'guest_time' => $guest_time,
                    'guest_time_unix' => $guest_time_unix,
                    'user_viewed' => 0
                );
                $this->users_model->updateGuestByUserIdAndGuestId($user_id, $guest_id, $data_guests);
            }
            $data_users = array(
                'users' => $this->users_model->getUserById($user_id),
                'user_invite_num_rows' => $this->users_model->getUserInviteNumRowsByUserIdAndInvitedUserId($user_id, $guest_id),
                'friend_num_rows' => $this->users_model->getFriendNumRowsByUserIdAndFriendId($user_id, $guest_id),
                'user_friends' => $this->users_model->getFriendsByUserId($user_id),
                'user_albums' => $this->albums_model->getAlbumsByUserId($user_id),
                'user_books' => $this->books_model->getBookFansByFanUserId($user_id),
                'user_events' => $this->events_model->getEventFansByFanUserId($user_id),
                'user_songs' => $this->songs_model->getSongFansByFanUserId($user_id),
                'user_gifts' => $this->gifts_model->getGiftSentByUserId($user_id),
                'user_stakes' => $this->stakes_model->getStakeFansByFanUserId($user_id),
                'user_page_emotion_num_rows' => $this->users_model->getUserPageEmotionNumRowsByUserIdAndEmotionedUserId($user_id, $guest_id),
                'total_user_page_emotions' => $this->users_model->getTotalByUserIdAndUserTable($user_id, 'user_page_emotions'),
                'total_friends' => $this->users_model->getTotalByUserIdAndUserTable($user_id, 'friends'),
                'total_books' => $this->users_model->getTotalByFanUserIdAndFanTable($user_id, "book_fans"),
                'total_events' => $this->users_model->getTotalByFanUserIdAndFanTable($user_id, "event_fans"),
                'total_songs' => $this->users_model->getTotalByFanUserIdAndFanTable($user_id, "song_fans"),
                'user_num_rows' => $user_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash(),
                'user_id' => $user_id
            );
            $this->load->view('one_user', $data_users);
        } else {
            $data_users = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'user_num_rows' => $user_num_rows
            );
            $this->load->view('one_user', $data_users);
        }
    }

    public function Logout() {
        session_start();
        session_destroy();
        redirect('/');
    }

    public function Header() {
        $email = $_SESSION['user_email'];
        $users = $this->users_model->getOneUserByEmail($email);
        $data = array(
            'users' => $users
        );
        $this->load->view('header', $data);
    }

    public function search_users() {
        $this->load->view('session_user');
        $search = $this->input->get('search');
        $session_user_id = $_SESSION['user_id'];
        $html = '';
        if ($search != '') {
            $users = $this->users_model->searchUsers($search);
            if (count($users) == 0) {
                $html .= "<h3 class='centered'>По Вашему запросу $search никто не найден.</h3>";
            } else {
                $html .= "<h3 class='centered'>Результаты по поиску $search</h3>";
                foreach ($users as $user) {
                    $id = $user->id;
                    $email = $user->email;
                    $nickname = $user->nickname;
                    $surname = $user->surname;
                    $main_image = $user->main_image;
                    $last_visit = $user->last_visit;
                    $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($session_user_id, $id);
                    $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($session_user_id, $id);
                    $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($session_user_id, $id);
                    $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($session_user_id, $id);

                    $user_num_rows = $this->users_model->getFriendNumRowsByUserIdAndFriendId($id, $session_user_id);
                    $friend_num_rows = $this->users_model->getFriendNumRowsByUserIdAndFriendId($session_user_id, $id);

                    $html .= "<div class='row one-friend'>
                        <div class='col-xs-12'>
                            <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                               <div class='centered'>
                                <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                    if ($last_visit == "Online") {
                        $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                    }
                    $html .= "</a>
                       </div>";
                    if($id != $session_user_id) {
                        $html .= "<div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$session_user_id' data-search_user_id='$id'>";
                        if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                            $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                        } else {
                            if ($total_common_friends > 0) {
                                $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                            }
                            if ($total_common_books > 0) {
                                $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                            }
                            if ($total_common_events > 0) {
                                $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                            }
                            if ($total_common_songs > 0) {
                                $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                            }
                        }
                        $html .= "</ul>
                            </div>";
                    }
                    if($id == $session_user_id) {
                        $html .= "<div class='centered'>
                            Это Вы <img src='" . base_url() . "uploads/icons/checked.png'>
                       </div>";
                    } else if ($user_num_rows == 1 && $friend_num_rows == 1) {
                        $html .= "<div class='centered'>
                            Вы - друзья <img src='" . base_url() . "uploads/icons/checked.png'>
                       </div>";
                    }
                        $html .= "</div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                        <a class='link-friend' href='" . base_url() . "one_user/$email'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </a>
                        </div></div>
                   </div>";
                }
            }
            $data = array(
                'search' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'search' => '<h4 class="centered red">Вы ввели пустой поиск. Так нельзя.</h4>',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('search_users', $data);
    }

    public function insert_currency() {
        $id = $_SESSION['user_id'];
        $currency = $this->input->post('currency');

        if ($currency != '') {
            $currency_before = $this->users_model->getCurrencyById($id);

            $data_users = array(
                'currency' => $currency_before + $currency
            );
            $this->users_model->updateUserById($id, $data_users);
            $currency_after = $this->users_model->getCurrencyById($id);

            $insert_json = array(
                'currency' => $currency_after,
                'csrf_hash' => $this->security->get_csrf_hash(),
                'currency_success' => "Ваш счёт успешно пополнен на $currency сомов. Теперь у Вас на счету $currency_after сомов."
            );
        } else {
            $insert_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'currency_error' => "Не удалось пополнить счёт."
            );
        }
        echo json_encode($insert_json);
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

    public function search_gift_users() {
        $search_by_name = $this->input->post('search_by_name');
        $user_id = $_SESSION['user_id'];
        $html = '';
        if (iconv_strlen($search_by_name) > 0) {
            $html .= "<h3 class='centered'>Результаты по запросу $search_by_name</h3>";
            $users = $this->users_model->searchUsers($search_by_name);
            if (count($users) == 0) {
                $html .= "<div class='red centered'>По Вашему запросу $search_by_name ничего не найдено! :(</div>";
            }
        } else {
            $users = $this->users_model->getFriendsByUserId($user_id);
        }

        foreach ($users as $user) {
            $html.= "<div onclick='chooseUserId(this)' data-user_id='$user->id' class='col-xs-6 col-sm-4 col-lg-3 friend_$user->id friend centered'>
                <div class='friend_user_image'>
                    <img src='uploads/images/user_images/$user->main_image' class='friend_avatar'>
                </div>
                <div class='friend_name'>
                    $user->nickname $user->surname
                </div>
            </div>";
        }

        $data = array(
            'csrf_hash'=> $this->security->get_csrf_hash(),
            'search_gift_users' => $html
        );
        echo json_encode($data);
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
        $num_rows = $this->users_model->getUserNumRowsByEmail($email);

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
                'last_visit' => 'Online',
                'currency' => 100,
                'rating' => 100,
                'rank' => "Новичок",
                'sign_date' => $date_mk,
                'sign_time' => $time_mk
            );
            $this->users_model->insertUser($data_users);
            $insert_user_id = $this->db->insert_id();

            $data_user_albums = array(
                'album_name' => "My Album",
                'user_id' => $insert_user_id
            );
            $this->albums_model->insertAlbum($data_user_albums);

            $data_publication_albums = array(
                'album_name' => "Publication Album",
                'user_id' => $insert_user_id
            );
            $this->albums_model->insertAlbum($data_publication_albums);

            $data_folders = array(
                'folder_name' => "My Folder",
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
        }
        $song_suggestions = $this->songs_model->getSongSuggestionsBySuggestedUserId($id);
        foreach ($song_suggestions as $song_suggestion) {
            $song_suggestion_file = $song_suggestion->suggestion_file;
            unlink("./uploads/song_files/$song_suggestion_file");
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
        $this->users_model->deleteUserImageEmotionsByUserIdOrEmotionedUserId($id);
        $this->users_model->deleteUserImagesByUserId($id);
        $this->users_model->deleteUserInvitesByUserIdOrInvitedUserId($id);
        $this->users_model->deleteUserNotificationsByUserId($id);
        $this->users_model->deleteUserPageEmotionsByUserIdOrEmotionedUserId($id);
        $this->users_model->deleteUserById($id);

        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update() {
        $this->load->view('session_user');
        $id = $_SESSION['user_id'];
        $users = $this->users_model->getOneUserById($id);
        $data = array(
            'users' => $users,
            'countries' => $this->countries_model->getCountries(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('update', $data);
    }

    public function update_user() {
        $id = $this->input->post('id');

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($id == $_SESSION['user_id']) {

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

            $db_current_email = $this->users_model->getEmailById($id);
            $db_home_land = $this->users_model->getHomeLandById($id);
            $db_birth_date = $this->users_model->getBirthDateById($id);
            $db_birth_year = $this->users_model->getBirthYearById($id);
            $db_education_schools = $this->users_model->getEducationSchoolsById($id);
            $db_education_universities = $this->users_model->getEducationUniversitiesById($id);
            $db_family_position = $this->users_model->getFamilyPositionById($id);

            $num_rows = $this->users_model->getNumRowsByEmailAndCurrentEmail($email, $db_current_email);

            if ($num_rows > 0 || strlen($email) < 5 || empty($email) || empty($nickname) || strlen($nickname) < 2 || empty($surname)
                || strlen($surname) < 2 || ($birth_date == '30 февраля' && $birth_year != '') || ($birth_date == '31 февраля' && $birth_year != '') || ($birth_date == '31 апреля' && $birth_year != '')
                || ($birth_date == '31 июня' && $birth_year != '') || ($birth_date == '31 сентября' && $birth_year != '') || ($birth_date == '31 ноября' && $birth_year != '')
                || ($birth_date == '29 февраля' && $birth_year % 4 != 0)
                || ($day == '' && $month != '') || ($day != '' && $month == '') || ($day != '' && $month != '' && $birth_year == '') || ($birth_date == ' ' && $birth_year != '')
            ) {
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
                if (($birth_date == '30 февраля' && $birth_year != '') || ($birth_date == '31 февраля' && $birth_year != '') || ($birth_date == '31 апреля' && $birth_year != '')
                    || ($birth_date == '31 июня' && $birth_year != '') || ($birth_date == '31 сентября' && $birth_year != '') || ($birth_date == '31 ноября' && $birth_year != '')
                    || ($birth_date == '29 февраля' && $birth_year % 4 != 0) || ($day == '' && $month != '') || ($day != '' && $month == '') || ($day != '' && $month != '' && $birth_year == '') || ($birth_date == ' ' && $birth_year != '')
                ) {
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
                    $education_schools = $db_education_schools;
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
                    $education_universities = $db_education_universities;
                }

                $birth_date = $birth_date == ' ' ? $db_birth_date : $birth_date;
                $birth_year = $birth_year == '' ? $db_birth_year : $birth_year;
                $home_land = $home_land == '' ? $db_home_land : $home_land;
                $family_position = $family_position == '' ? $db_family_position : $family_position;
                $education_schools = $education_schools == '' ? $db_education_schools : $education_schools;
                $education_universities = $education_universities == '' ? $db_education_universities : $education_universities;

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
                );
                $this->users_model->updateUserById($id, $data_users);
                $_SESSION['user_email'] = $email;
                $messages['success_update'] = 'Ваши данные успешно обновлены!';
            }
        } else {
            $messages['user_error'] = 'Вы не можете менять данные, потому что это не Ваш идентификатор';
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }
    public function update_password() {
        $id = $this->input->post('id');
        $session_user_id = $_SESSION['user_id'];
        $db_password = $this->users_model->getPasswordById($id);
        $current_password = md5($_POST['current_password']);
        $new_password = $this->input->post('new_password');
        $check_new_password = $this->input->post('check_new_password');

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($id != $session_user_id) {
            $messages['user_error'] = "Не удалось изменить пароль, так как это не Ваш идентификтор";
        } else {
            if ($db_password != $current_password || $new_password != $check_new_password || empty($new_password)
                || strlen($new_password) < 6
            ) {

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
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }
    public function update_secret_questions_and_secret_answers() {
        $id = $this->input->post('id');
        $session_user_id = $_SESSION['user_id'];
        $secret_question_1 = $this->input->post('secret_question_1');
        $secret_answer_1 = $this->input->post('secret_answer_1');
        $secret_question_2 = $this->input->post('secret_question_2');
        $secret_answer_2 = $this->input->post('secret_answer_2');

        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        if ($id != $session_user_id) {
            $messages['user_error'] = 'Не удалось поменять секретные вопросы, так как это не Ваш идентификатор';
        } else {
            if (empty($secret_question_1) || empty($secret_answer_1) || empty($secret_question_2) || empty($secret_answer_2)) {
                if (empty($secret_question_1)) {
                    $messages['secret_question_1_empty'] = 'Новый секретный вопрос 1 не выбран, выберите из списка!';
                }
                if (empty($secret_answer_1)) {
                    $messages['secret_answer_1_empty'] = 'Ответ на новый 1 секретный вопрос пуст, введите свой ответ!';
                }
                if (empty($secret_question_2)) {
                    $messages['secret_question_2_empty'] = 'Новый секретный вопрос 2 не выбран, выберите из списка!';
                }
                if (empty($secret_answer_2)) {
                    $messages['secret_answer_2_empty'] = 'Ответ на новый 2 секретный вопрос пуст, введите свой ответ!';
                }
            } else {
                $data_users = array(
                    'secret_question_1' => $secret_question_1,
                    'secret_answer_1' => $secret_answer_1,
                    'secret_question_2' => $secret_question_2,
                    'secret_answer_2' => $secret_answer_2,
                );
                $this->users_model->updateUserById($id, $data_users);
                $messages['success_secret'] = "Секретные вопросы и ответы успешно изменены!";
            }
        }
        $messages_json = json_encode($messages);
        echo $messages_json;
    }

    public function restore() {
        $data = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('restore', $data);
    }

    public function check_email() {
        $email = $this->input->post('email');
        $num_rows = $this->users_model->getNumRowsByEmail($email);

        if ($num_rows > 0) {

            $first_secret_question = $this->users_model->getFirstSecretQuestionByEmail($email);
            $second_secret_question = $this->users_model->getSecondSecretQuestionByEmail($email);

            $check_json = array(
                'email_success' => "Такой емайл существует. Переходим к следующему шагу",
                'first_secret_question' => $first_secret_question,
                'second_secret_question' => $second_secret_question,
                'email' => $email,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $check_json = array(
                'email_error' => "Такого емайла не существует!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($check_json);
    }

    public function check_secret_answers() {
        $email = $this->input->post('email');
        $first_secret_answer = $this->input->post('first_secret_answer');
        $second_secret_answer = $this->input->post('second_secret_answer');

        if ($email == '') {
            $check_json = array(
                'answers_error' => "Вы пытаетесь восстановить чужой аккаунт. Так нельзя.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $db_first_secret_question = $this->users_model->getFirstSecretAnswerByEmail($email);
            $db_second_secret_question = $this->users_model->getSecondSecretAnswerByEmail($email);

            if ($first_secret_answer == $db_first_secret_question && $second_secret_answer == $db_second_secret_question) {
                $check_json = array(
                    'email' => $email,
                    'answers_success' => "Переходим к следующему шагу",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            } else {
                $check_json = array(
                    'answers_error' => "Секретные ответы неправильные!",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            }
        }
        echo json_encode($check_json);

    }

    public function set_new_password() {
        $email = $this->input->post('email');
        $password = $_POST['new_password'];

        if ($email == '') {
            $check_json = array(
                'password_error' => "Вы пытаетесь восстановить чужой аккаунт. Так нельзя.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $id = $this->users_model->getUserIdByEmail($email);
            $num_rows = $this->users_model->getNumRowsByEmail($email);
            if ($num_rows > 0 && strlen($password) >= 6) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_email'] = $email;
                $data_users = array(
                    'password' => md5($password)
                );
                $this->users_model->updateUserById($id, $data_users);
                $check_json = array(
                    'password_success' => "Ура!",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            } else {
                $check_json = array(
                    'password_error' => "Пароль слишком мал.",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            }
        }

        echo json_encode($check_json);

    }
}