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
        $commented_user_id = $this->input->post('commented_user_id');
        $song_id = $this->input->post('song_id');

        $data_song_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'commented_user_id' => $commented_user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongComment($data_song_comments);

        // НАДО ДОДЕЛАТЬ ЭКШН

        $song_action = 'Пользователь Назар прокомментировал песню "A MILLION VOICES"';

        $data_song_actions = array(
            'song_action' => $song_action,
            'song_time_unix' => time(),
            'action_user_id' => $commented_user_id,
            'song_id' => $song_id
        );
        $this->songs_model->insertSongAction($data_song_actions);
    }

    public function delete_song_comment() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongCommentById($id);
        $this->songs_model->deleteSongCommentComplaintsBySongCommentId($id);
        $this->songs_model->deleteSongCommentEmotionsBySongCommentId($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}