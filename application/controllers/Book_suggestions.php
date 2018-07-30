<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'book' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ книг.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Пользователь</td>
                    <td>Проверка книги</td>
                    <td>Удалить предложение</td>
                    <td>Принять предложение</td>
                </tr>";

            $book_suggestions = $this->books_model->getBookSuggestionsByAdminId($admin_id);

            foreach ($book_suggestions as $book_suggestion) {
                $id = $book_suggestion->id;
                $suggestion_json = $book_suggestion->suggestion_json;
                $book_file = $book_suggestion->suggestion_file;
                $book_image = $book_suggestion->suggestion_image;
                $suggested_user_id = $book_suggestion->suggested_user_id;
                $email = $book_suggestion->email;
                $html .= "<tr class='one-suggestion-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>
                            <button onclick='getOneBookSuggestionByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneBookSuggestion' data-id='$id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressBookSuggestion(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteBookSuggestion' data-id='$id' data-file='$book_file' data-image='$book_image'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressBookSuggestionAndInsertPressBook(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteBookSuggestionAndInsertBook' data-id='$id' data-suggested_user_id='$suggested_user_id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'book_suggestions' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'suggestion_error' => 'У вас нет прав на просмотр предложений на книги',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
    }

    public function insert_book_suggestion() {
        $messages = array(
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $transliteration = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ь' => '\'',
            'ы' => 'y', 'ъ' => '\'\'', 'э' => 'e\'', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'J',
            'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S',
            'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH',
            'Щ' => 'SHH', 'Ь' => '\'', 'Ы' => 'Y\'', 'Ъ' => '\'\'', 'Э' => 'E\'', 'Ю' => 'YU', 'Я' => 'YA'
        );
        $book_name = $this->input->post('book_name');
        $book_author = $this->input->post('book_author');
        $book_description = $this->input->post('book_description');
        $category_id = $this->input->post('category_id');
        $book_file = strtr(preg_replace('/[ \t]+/', '_', $_FILES['book_file']['name']), $transliteration);
        $book_image = strtr(preg_replace('/[ \t]+/', '_', $_FILES['book_image']['name']), $transliteration);

        $book_file_extension = pathinfo($book_file, PATHINFO_EXTENSION);
        $book_image_extension = pathinfo($book_image, PATHINFO_EXTENSION);
        if( $book_file_extension != 'pdf' || $book_image_extension != 'jpg') {
            if ($book_file_extension != 'pdf') {
                $messages['book_file_error'] = "Файл не в PDF-формате. Загрузите PDF-файл";
            }
            if ($book_image_extension != 'jpg') {
                $messages['book_image_error'] = "Файл не в JPG-формате. Загрузите JPG-фотку";
            }
        } else {
            $book_file = strtr(preg_replace('/[ \t]+/', '_', $book_name), $transliteration) . '.' . $book_file_extension;
            $book_image = strtr(preg_replace('/[ \t]+/', '_', $book_name), $transliteration) . '.' . $book_image_extension;

            $this->load->library('upload');
            $config['upload_path'] = './uploads/book_files';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = $book_file;
            $this->upload->initialize($config);

            $this->upload->do_upload('book_file');

            $config['upload_path'] = './uploads/images/book_images';
            $config['allowed_types'] = 'jpg';
            $config['file_name'] = $book_image;
            $this->upload->initialize($config);

            $this->upload->do_upload('book_image');

            $suggestion_json = "[{\"book_name\": \"$book_name\", \"book_file\": \"$book_file\", \"book_author\": \"$book_author\", \"book_description\": \"$book_description\",
               \"book_image\": \"$book_image\", \"category_id\": \"$category_id\"}]";
            $suggestion_date = date('d.m.Y');
            $suggestion_time = date('H:i:s');
            $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('books');
            $suggested_user_id = $_SESSION['user_id'];

            $data_book_suggestions = array(
                'suggestion_json' => $suggestion_json,
                'suggestion_file' => $book_file,
                'suggestion_image' => $book_image,
                'suggestion_date' => $suggestion_date,
                'suggestion_time' => $suggestion_time,
                'admin_id' => $admin_id,
                'suggested_user_id' => $suggested_user_id
            );
            $this->books_model->insertBookSuggestion($data_book_suggestions);
            $messages['success_suggestion'] = 'Книга ' . $book_name . ' успешно предложена Вами, отправлена админу и будет рассмотрена в ближайшее время';
        }
        echo json_encode($messages);
    }

    public function delete_book_suggestion() {
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $book_suggestion_file = $this->input->post('book_file');
            $book_suggestion_image = $this->input->post('book_image');
            unlink("./uploads/book_files/$book_suggestion_file");
            unlink("./uploads/images/book_images/$book_suggestion_image");
            $this->books_model->deleteBookSuggestionById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил предложение на добавление книги'",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'suggestion_error' => 'Не удалось удалить предложение',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_book_suggestion_and_insert_book() {

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $this->books_model->deleteBookSuggestionById($id);

            $book_name = $this->input->post('book_name');
            $book_file = $this->input->post('book_file');
            $book_author = $this->input->post('book_author');
            $book_description = $this->input->post('book_description');
            $book_image = $this->input->post('book_image');
            $category_id = $this->input->post('category_id');
            $suggested_user_id = $this->input->post('suggested_user_id');

            $data_books = array(
                'book_name' => $book_name,
                'book_file' => $book_file,
                'book_author' => $book_author,
                'book_description' => $book_description,
                'book_image' => $book_image,
                'category_id' => $category_id,
            );

            $this->books_model->insertBook($data_books);
            $insert_book_id = $this->db->insert_id();

            $data_admin_actions = array(
                'admin_action' => "$admin_email добавил книгу '$book_name'",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);

            $user_name = $this->users_model->getNicknameAndSurnameById($suggested_user_id);

            $data_book_actions = array(
                'book_action' => "Предложенную книгу $book_name пользователя $user_name опубликовали админы.",
                'book_time_unix' => time(),
                'action_user_id' => $suggested_user_id,
                'book_id' => $insert_book_id
            );
            $this->books_model->insertBookAction($data_book_actions);

            $notification_date = date('d.m.Y');
            $notification_time = date('H:i:s');
            $notification_text = "Админ одобрил Вашу предложенную книгу $book_name. К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.";

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашей книги',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => $insert_book_id,
                'link_table' => 'books',
                'user_id' => $suggested_user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($suggested_user_id);
            $rating_before = $this->users_model->getRatingById($suggested_user_id);

            $data_users = array(
                'currency' => $currency_before + 1,
                'rating' => $rating_before + 5
            );
            $this->users_model->updateUserById($suggested_user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($suggested_user_id);
            $rank_after = $this->users_model->getRankById($suggested_user_id);
            $this->users_model->updateRankById($suggested_user_id, $rating_after, $rank_after);

            $insert_json = array(
                'id' => $id,
                'suggestion_success' => 'Книга добавлена',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'suggestion_error' => 'Не удалось добавить книгу',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

}