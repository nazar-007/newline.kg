<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_share_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $data = array(
            'publications' => $this->publications_model->getPublications(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

    public function insert_publication_share_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $publication_share_id = $this->input->post('publication_share_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_share_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'publication_share_id' => $publication_share_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->insertPublicationShareEmotion($data_publication_share_emotions);
    }

    public function delete_publication_share_emotion() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationShareEmotion($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}