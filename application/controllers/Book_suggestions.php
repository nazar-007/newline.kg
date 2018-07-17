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
        $admin_id = 1;
        $data = array(
            'book_suggestions' => $this->books_model->getBookSuggestionsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('book_suggestions', $data);
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
        if( $book_file_extension != 'pdf' || $book_image_extension != 'png') {
            if ($book_file_extension != 'pdf') {
                $messages['book_file_error'] = "Файл не в PDF-формате. Загрузите PDF-файл";
            }
            if ($book_image_extension != 'png|jpg') {
                $messages['book_image_error'] = "Файл не в Png-формате. Загрузите Png-фотку";
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
            $config['allowed_types'] = 'png';
            $config['file_name'] = $book_image;
            $this->upload->initialize($config);

            $this->upload->do_upload('book_image');

            $suggestion_json = "[{'book_name': '$book_name', 'book_file': '$book_file', 'book_author': '$book_author', 'book_description': '$book_description',
               'book_image': '$book_image', 'category_id': '$category_id'}]";
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
        $id = $this->input->post('id');
        $book_suggestion_file = $this->books_model->getBookSuggestionFileById($id);
        $book_suggestion_image = $this->books_model->getBookSuggestionImageById($id);
        $book_name = $this->input->post('book_name');
        $user_id = $this->input->post('user_id');
        unlink("./uploads/book_files/$book_suggestion_file");
        unlink("./uploads/images/book_images/$book_suggestion_image");
        $this->books_model->deleteBookSuggestionById($id);
        $notification_text = 'Админ не одобрил Вашу предложенную книгу ' . $book_name . '.';

        $data_user_notifications = array(
            'notification_type' => 'Отказ от предложенной книги',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'link_id' => 0,
            'link_table' => 0,
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_suggestion() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_book_suggestions = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->books_model->updateBookSuggestionById($id, $data_book_suggestions);
    }

}