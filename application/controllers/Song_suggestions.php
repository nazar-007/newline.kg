<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_suggestions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $admin_id = 1;
        $data = array(
            'song_suggestions' => $this->songs_model->getSongSuggestions($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_suggestions', $data);
    }


    public function insert_song_suggestion() {
        $suggestion_json = $this->input->post('suggestion_json');
        $suggestion_file = $this->input->post('suggestion_file');
        $suggestion_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $user_id = $this->input->post('user_id');

        $data_song_suggestions = array(
            'suggestion_json' => $suggestion_json,
            'suggestion_file' => $suggestion_file,
            'suggestion_time_unix' => $suggestion_time_unix,
            'admin_id' => $admin_id,
            'user_id' => $user_id
        );
        $this->songs_model->insertSongSuggestion($data_song_suggestions);
    }

    public function delete_song_suggestion() {
        // надо удалять файлы тоже

//        $id = $this->input->post('id');
//        $this->users_model->deleteUserBlacklist($id);
//        $delete_json = array(
//            'id' => $id,
//            'csrf_name' => $this->security->get_csrf_token_name (),
//            'csrf_hash' => $this->security->get_csrf_hash()
//        );
//        echo json_encode($delete_json);
    }
}