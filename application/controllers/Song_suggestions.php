<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'song_suggestions' => $this->songs_model->getSongSuggestionsByAdminId($admin_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_suggestions', $data);
    }

    public function insert_song_suggestion() {
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
        $song_name = $this->input->post('song_name');
        $song_singer = $this->input->post('song_singer');
        $song_lyrics = $this->input->post('song_lyrics');
        $category_id = $this->input->post('category_id');
        $song_file = strtr(preg_replace('/[ \t]+/', '_', $_FILES['song_file']['name']), $transliteration);

        $song_file_extension = pathinfo($song_file, PATHINFO_EXTENSION);
        if ($song_file_extension != 'mp3') {
            $messages['song_file_error'] = "Файл не в MP3-формате. Загрузите MP3-файл";
        } else {
            $song_file = strtr(preg_replace('/[ \t]+/', '_', $song_name), $transliteration) . '.' . $song_file_extension;

            $this->load->library('upload');
            $config['upload_path'] = './uploads/song_files';
            $config['allowed_types'] = 'mp3';
            $config['file_name'] = $song_file;
            $this->upload->initialize($config);
            $this->upload->do_upload('song_file');

            $suggestion_json = "[{'song_name': '$song_name', 'song_file': '$song_file', 'song_singer': '$song_singer', 'song_lyrics': '$song_lyrics',
               category_id': '$category_id'}]";
            $suggestion_date = date('d.m.Y');
            $suggestion_time = date('H:i:s');
            $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('songs');
            $suggested_user_id = $_SESSION['user_id'];

            $data_song_suggestions = array(
                'suggestion_json' => $suggestion_json,
                'suggestion_file' => $song_file,
                'suggestion_date' => $suggestion_date,
                'suggestion_time' => $suggestion_time,
                'admin_id' => $admin_id,
                'suggested_user_id' => $suggested_user_id
            );
            $this->songs_model->insertSongSuggestion($data_song_suggestions);
            $messages['success_suggestion'] = 'Песня ' . $song_name . ' успешно предложена Вами, отправлена админу и будет рассмотрена в ближайшее время';
        }
        echo json_encode($messages);
    }


    public function delete_song_suggestion() {
        $id = $this->input->post('id');
        $song_suggestion_file = $this->songs_model->getSongSuggestionFileById($id);
        $song_name = $this->input->post('song_name');
        $user_id = $this->input->post('user_id');
        unlink("./uploads/song_files/$song_suggestion_file");
        $this->songs_model->deleteSongSuggestionById($id);

        $notification_text = 'Админ не одобрил Вашу предложенную песню ' . $song_name . '.';

        $data_user_notifications = array(
            'notification_type' => 'Отказ от предложенной песни',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('d.m.Y'),
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

    public function update_song_suggestion() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_song_suggestions = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->songs_model->updateSongSuggestionById($id, $data_song_suggestions);
    }
}