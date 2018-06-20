<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_fans extends CI_Controller {

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
        $this->load->view('events', $data);
    }

    public function insert_song_fan() {
        $fan_date = date('d.m.Y');
        $fan_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $song_id = $this->input->post('song_id');

        $data_song_fans = array(
            'fan_date' => $fan_date,
            'fan_time' => $fan_time,
            'user_id' => $user_id,
            'song_id' => $song_id,
        );
        $this->songs_model->insertSongFan($data_song_fans);
    }

    public function delete_song_fan() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongFan($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}