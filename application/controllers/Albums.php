<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('albums_model');
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

    public function insert_album() {
        $album_name = $this->input->post('album_name');
        $user_id = $this->input->post('user_id');

        $data_albums = array(
            'album_name' => $album_name,
            'user_id' => $user_id
        );

        if ($album_name != 'My Album' || $album_name != 'Publication Album') {
            $this->albums_model->insertAlbum($data_albums);
        }
    }
}