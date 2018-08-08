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
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'song' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ песен.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Пользователь</td>
                    <td>Проверка песни</td>
                    <td>Удалить предложение</td>
                    <td>Принять предложение</td>
                </tr>";

            $song_suggestions = $this->songs_model->getSongSuggestionsByAdminId($admin_id);

            foreach ($song_suggestions as $song_suggestion) {
                $id = $song_suggestion->id;
                $suggestion_json = $song_suggestion->suggestion_json;
                $song_file = $song_suggestion->suggestion_file;
                $suggested_user_id = $song_suggestion->suggested_user_id;
                $email = $song_suggestion->email;
                $html .= "<tr class='one-suggestion-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>
                            <button onclick='getOneSongSuggestionByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneSongSuggestion' data-id='$id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressSongSuggestion(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteSongSuggestion' data-id='$id' data-file='$song_file'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressSongSuggestionAndInsertPressSong(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteSongSuggestionAndInsertSong' data-id='$id' data-suggested_user_id='$suggested_user_id' data-suggestion-json='$suggestion_json'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'song_suggestions' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'suggestion_error' => 'У вас нет прав на просмотр предложений на песни',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
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
        $song_lyrics = nl2br(addslashes($_POST['song_lyrics']));
        $song_lyrics = str_replace('<br />', '<br>', $song_lyrics);
        $category_id = $this->input->post('category_id');
        $song_file = strtr(preg_replace('/[ \t]+/', '_', $_FILES['song_file']['name']), $transliteration);

        $song_file_extension = pathinfo($song_file, PATHINFO_EXTENSION);
        if ($song_file_extension != 'mp3') {
            $messages['song_file_error'] = "Файл не в MP3-формате. Загрузите MP3-файл";
        } else {
            $song_file_name = $song_singer . ' - ' . $song_name;
            $song_file = strtr(preg_replace('/[ \t]+/', '_', $song_file_name), $transliteration) . '.' . $song_file_extension;

            $this->load->library('upload');
            $config['upload_path'] = './uploads/song_files';
            $config['allowed_types'] = 'mp3';
            $config['file_name'] = $song_file;
            $this->upload->initialize($config);
            $this->upload->do_upload('song_file');

            $suggestion_json = "[{\"song_name\": \"$song_name\", \"song_file\": \"$song_file\", \"song_singer\": \"$song_singer\",
             \"song_lyrics\": \"$song_lyrics\", \"category_id\": \"$category_id\"}]";
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
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $song_suggestion_file = $this->input->post('song_file');
            unlink("./uploads/song_files/$song_suggestion_file");
            $this->songs_model->deleteSongSuggestionById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил предложение на добавление песни'",
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

    public function delete_song_suggestion_and_insert_song() {
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($admin_id && $admin_email && $admin_table) {
            $id = $this->input->post('id');
            $this->songs_model->deleteSongSuggestionById($id);

            $song_name = $this->input->post('song_name');
            $song_file = $this->input->post('song_file');
            $song_singer = $this->input->post('song_singer');
            $song_lyrics = $this->input->post('song_lyrics');
            $category_id = $this->input->post('category_id');
            $suggested_user_id = $this->input->post('suggested_user_id');

            $data_songs = array(
                'song_name' => $song_name,
                'song_file' => $song_file,
                'song_singer' => $song_singer,
                'song_lyrics' => $song_lyrics,
                'category_id' => $category_id,
            );

            $this->songs_model->insertSong($data_songs);
            $insert_song_id = $this->db->insert_id();

            $data_admin_actions = array(
                'admin_action' => "$admin_email добавил песню '$song_name'",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);

            $user_name = $this->users_model->getNicknameAndSurnameById($suggested_user_id);

            $data_song_actions = array(
                'song_action' => "Предложенную песню $song_name пользователя $user_name опубликовали админы.",
                'song_time_unix' => time(),
                'action_user_id' => $suggested_user_id,
                'song_id' => $insert_song_id
            );
            $this->songs_model->insertSongAction($data_song_actions);

            $notification_date = date('d.m.Y');
            $notification_time = date('H:i:s');
            $notification_text = "Админ одобрил Вашу предложенную песню $song_name. К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.";

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашей песни',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => $insert_song_id,
                'link_table' => 'songs',
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
                'suggestion_success' => 'Песня добавлена',
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