<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_comment_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $admin_id = 2;
        $data = array(
            'song_comment_complaints' => $this->songs_model->getSongCommentComplaintsByAdminId($admin_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('song_comment_complaints', $data);
    }

    public function insert_song_comment_complaint() {
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->input->post('admin_id');
        $song_id = $this->input->post('song_id');
        $song_comment_id = $this->input->post('song_comment_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $data_song_comment_complaints = array(
            'complaint_text' => $complaint_text,
            'complaint_time_unix' => $complaint_time_unix,
            'admin_id' => $admin_id,
            'song_id' => $song_id,
            'song_comment_id' => $song_comment_id,
            'commented_user_id' => $commented_user_id,
            'complained_user_id' => $complained_user_id
        );

        $this->songs_model->insertSongCommentComplaint($data_song_comment_complaints);
    }

    public function delete_song_comment_complaint() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongCommentComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_song_comment_complaints_by_complained_user_id() {
        $complained_user_id = $this->input->post('complained_user_id');
        $this->songs_model->deleteSongCommentComplaintsByComplainedUserId($complained_user_id);
        $delete_json = array(
            'complained_user_id' => $complained_user_id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}