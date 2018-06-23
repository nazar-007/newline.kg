<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'songs' => $this->songs_model->getSongsByCategoryIds($category_ids),
            'song_categories' => $this->songs_model->getSongCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('songs', $data);
    }

    public function choose_song_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $songs = $this->songs_model->getSongsByCategoryIds($category_ids);
        foreach ($songs as $song) {
            echo "<tr>
            <td>$song->id</td>
            <td>
                <a href='" . base_url() . "models/" . $song->id . "'>" . $song->song_name . "</a>
            </td>
         </tr>";
        }
    }

}