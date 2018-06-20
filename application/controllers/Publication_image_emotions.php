<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_image_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $user_id = 1;
        $data = array(
            'albums' => $this->albums_model->getAlbums($user_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('albums', $data);
    }


    public function insert_publication_image_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $publication_image_id = $this->input->post('publication_image_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'user_id' => $user_id,
            'publication_image_id' => $publication_image_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->insertPublicationImageEmotion($data_publication_image_emotions);
    }

    public function delete_publication_image_emotion() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationImageEmotion($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}