<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'songs' => $this->songs_model->getSongsByCategoryIds($category_ids),
            'song_categories' => $this->songs_model->getSongCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('songs', $data);
    }

    public function choose_song_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $songs = $this->songs_model->getSongsByCategoryIds($category_ids);
        foreach ($songs as $song) {
            echo "<tr>
            <td>$song->id</td>
            <td>
                <a href='" . base_url() . "models/" . $song->id . "'>" . $song->song_name . "</a>
            </td>
         </tr>";
        }
    }

    public function One_song($id) {
        $song_num_rows = $this->songs_model->getSongNumRowsById($id);
        if ($song_num_rows == 1) {
            $data_songs = array(
                'one_song' => $this->songs_model->getOneSongById($id),
                'song_num_rows' => $song_num_rows
            );
        } else {
            echo "Песня удалена или ещё не добавлена!";
            $data_songs = array(
                'song_num_rows' => $song_num_rows
            );
        }
        $this->load->view('one_song', $data_songs);
    }

    public function insert_song() {
        $song_name = $this->input->post('song_name');
        $song_file = $this->input->post('song_file');
        $song_singer = $this->input->post('song_author');
        $song_lyrics = $this->input->post('song_description');
        $song_image = $this->input->post('song_image');
        $song_year = $this->input->post('song_year');
        $song_http_link = $this->input->post('song_http_link');
        $category_id = $this->input->post('category_id');
        $user_id = $this->input->post('suggested_user_id');

        $data_songs = array(
            'song_name' => $song_name,
            'song_file' => $song_file,
            'song_singer' => $song_singer,
            'song_lyrics' => $song_lyrics,
            'song_image' => $song_image,
            'song_year' => $song_year,
            'song_http_link' => $song_http_link,
            'category_id' => $category_id,
            'user_id' => $user_id
        );

        $this->songs_model->insertSong($data_songs);
        $insert_song_id = $this->db->insert_id();

        // НАДО ДОДЕЛАТЬ ЭКШН

        $song_action = 'Предложенную песню Назара "A million voices" одобрил админ.';
        $data_song_actions = array(
            'song_action' => $song_action,
            'song_time_unix' => time(),
            'action_user_id' => $user_id,
            'song_id' => $insert_song_id
        );
        $this->songs_model->insertSongAction($data_song_actions);

        $notification_date = date('d.m.Y');
        $notification_time = date('H:i:s');
        if ($user_id != 0) {
            $notification_text = 'Админ одобрил Вашу песню "A million voices". К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Одобрение Вашей песни',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before + 1,
                'rating' => $rating_before + 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);

            $suggestion_id = $this->input->post('suggestion_id');
            $this->songs_model->deleteSongSuggestionById($suggestion_id);
        }
    }

    public function delete_song() {
        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $song_file = $this->songs_model->getSongkFileById($id);
        $song_image = $this->songs_model->getSongImageById($id);
        unlink("./uploads/song_files/$song_file");
        unlink("./uploads/images/song_images/$song_image");

        $this->songs_model->deleteSongActionsBySongId($id);
        $this->songs_model->deleteSongCommentsBySongId($id);
        $this->songs_model->deleteSongComplaintsBySongId($id);
        $this->songs_model->deleteSongEmotionsBySongId($id);
        $this->songs_model->deleteSongFansBySongId($id);
        $this->songs_model->deleteSongById($id);

        $notification_date = date("d.m.Y");
        $notification_time = date("H:i:s");

        if ($user_id != 0) {
            $notification_text = 'Ваша одобренная песня "A million voices" удалена. С Вашей валюты снялся 1 сом, а с рейтинга - 5 баллов.';

            $data_user_notifications = array(
                'notification_type' => 'Удаление Вашей одобренной песни',
                'notification_text' => $notification_text,
                'notification_date' => $notification_date,
                'notification_time' => $notification_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);

            $currency_before = $this->users_model->getCurrencyById($user_id);
            $rating_before = $this->users_model->getRatingById($user_id);

            $data_users = array(
                'currency' => $currency_before - 1,
                'rating' => $rating_before - 5
            );
            $this->users_model->updateUserById($user_id, $data_users);

            $rating_after = $this->users_model->getRatingById($user_id);
            $rank_after = $this->users_model->getRankById($user_id);
            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);
        }

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_song() {
        $id = $this->input->post('id');
        $song_name = $this->input->post('song_name');
        $song_singer = $this->input->post('song_author');
        $song_lyrics = $this->input->post('song_description');
        $song_year = $this->input->post('song_year');
        $song_http_link = $this->input->post('song_http_link');

        $data_songs = array(
            'song_name' => $song_name,
            'song_singer' => $song_singer,
            'song_lyrics' => $song_lyrics,
            'song_year' => $song_year,
            'song_http_link' => $song_http_link
        );

        $this->songs_model->updateSongById($id, $data_songs);

    }

}