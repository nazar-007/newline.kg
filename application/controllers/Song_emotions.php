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
            'songs' => $this->songs_model->getSongs($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_emotions', $data);
    }

    public function insert_song_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $song_id = $this->input->post('song_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_song_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'song_id' => $song_id,
            'emotion_id' => $emotion_id
        );
        $this->songs_model->insertSongEmotion($data_song_emotions);
    }

    public function delete_song_emotion() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongEmotion($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}