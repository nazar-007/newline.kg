<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'song_suggestions' => $this->songs_model->getSongSuggestionsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_suggestions', $data);
    }

    public function insert_song_suggestion() {
        $song_name = $this->input->post('song_name');
        $song_singer = $this->input->post('song_singer');
        $song_lyrics = $this->input->post('song_lyrics');
        $song_year = $this->input->post('song_year');
        $song_http_link = $this->input->post('song_http_link');
        $category_id = $this->input->post('category_id');
        $suggestion_json = "[{'song_name': '$song_name', 'song_singer': '$song_singer', 'song_lyrics': '$song_lyrics', 
            'song_year': '$song_year', 'song_http_link': '$song_http_link', 'category_id': '$category_id'}]";
        $song_file = $this->input->post('song_file');
        $song_image = $this->input->post('song_image');
        $suggestion_date = date('d.m.Y');
        $suggestion_time = date('H:i:s');
        $admin_id = $this->input->post('admin_id');
        $suggested_user_id = $this->input->post('suggested_user_id');

        $data_song_suggestions = array(
            'suggestion_json' => $suggestion_json,
            'suggestion_file' => $song_file,
            'suggestion_image' => $song_image,
            'suggestion_date' => $suggestion_date,
            'suggestion_time' => $suggestion_time,
            'admin_id' => $admin_id,
            'suggestion_user_id' => $suggested_user_id
        );
        $this->songs_model->insertSongSuggestion($data_song_suggestions);
    }

    public function delete_song_suggestion() {
        $id = $this->input->post('id');
        $suggestion_file = $this->input->post('suggestion_file');
        $song_name = $this->input->post('song_name');
        $user_id = $this->input->post('user_id');
        unlink("./uploads/song_files/$suggestion_file");
        $this->songs_model->deleteSongSuggestionById($id);

        $notification_text = 'Админ не одобрил Вашу предложенную песню ' . $song_name . '.';

        $data_user_notifications = array(
            'notification_type' => 'Отказ от предложенной песни',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('d.m.Y'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}