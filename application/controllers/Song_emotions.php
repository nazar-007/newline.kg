<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'songs' => $this->songs_model->getSongsByCategoryIds($category_ids),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_emotions', $data);
    }

    public function insert_song_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $song_id = $this->input->post('song_id');

        $data_song_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'emotioned_user_id' => $emotioned_user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongEmotion($data_song_emotions);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $song_action = 'Пользователь Назар поставил эмоцию на песню "A MILLION VOICES"';

        $data_song_actions = array(
            'song_action' => $song_action,
            'song_date' => $emotion_date,
            'song_time_unix' => time(),
            'action_user_id' => $emotioned_user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongAction($data_song_actions);
    }

    public function delete_song_emotion() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}