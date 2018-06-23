<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'song_complaints' => $this->songs_model->getSongComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_complaints', $data);
    }

    public function insert_song_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $song_id = $this->input->post('song_id');
        $complained_id = $this->input->post('complained_id');

        $data_song_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'song_id' => $song_id,
            'complained_id' => $complained_id
        );

        $this->songs_model->insertSongComplaint($data_song_complaints);
    }

}