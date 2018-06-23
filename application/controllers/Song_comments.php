<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $song_id = $this->input->post('song_id');
        $data = array(
            'song_comments' => $this->songs_model->getSongCommentsBySongId($song_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_comments', $data);
    }

    public function insert_song_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $song_id = $this->input->post('song_id');

        $data_song_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'user_id' => $user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongComment($data_song_comments);
    }
}