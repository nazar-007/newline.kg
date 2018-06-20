<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $publication_id = 1;
        $data = array(
            'publication_comments' => $this->publications_model->getPublicationComments($publication_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_comments', $data);
    }

    public function insert_publication_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $user_id = $this->input->post('user_id');
        $publication_id = $this->input->post('publication_id');

        $data_publication_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'user_id' => $user_id,
            'publication_id' => $publication_id
        );
        $this->publications_model->insertPublicationComment($data_publication_comments);
    }
}