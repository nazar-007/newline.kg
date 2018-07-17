<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'songs' => $this->songs_model->getSongsByCategoryIds($category_ids),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('songs', $data);
    }

    public function insert_song_category() {
        $category_name = $this->input->post('category_name');
        $data_song_categories = array(
            'category_name' => $category_name,
        );
        $this->songs_model->insertSongCategory($data_song_categories);
    }
    public function delete_song_category() {
        $id = $this->input->post('id');
        $this->songs_model->deleteSongCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_song_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_song_categories = array(
            'category_name' => $category_name,
        );
        $this->songs_model->updateSongCategoryById($id, $data_song_categories);
    }
}