<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $song_id = 3;
        $data = array(
            'song_fans' => $this->songs_model->getSongFansBySongId($song_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_fans', $data);
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
            'song_id' => $song_id
        );
        $this->songs_model->insertSongFan($data_song_fans);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $song_action = 'Пользователь Назар добавил песню "A MILLION VOICES" в свою библиотеку';

        $data_song_actions = array(
            'song_action' => $song_action,
            'song_date' => $fan_date,
            'song_time' => $fan_time,
            'user_id' => $user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongAction($data_song_actions);
    }

    public function delete_song_fan() {

        // НАДО ДОДЕЛАТЬ !!!

        $id = $this->input->post('id');
        $this->songs_model->deleteSongFanById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}