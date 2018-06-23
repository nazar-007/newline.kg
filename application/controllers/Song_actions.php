<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'song_actions' => $this->songs_model->getSongActionsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_notifications', $data);
    }


}