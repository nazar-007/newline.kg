<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
    }

    public function Index() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $songs = $this->songs_model->getSongsByCategoryIds($category_ids, 0);
        $html = '';
        if (count($category_ids) == 0) {
            $html .= "<h3 class='centered'>Все песни</h3>";
        } else {
            $html .= "<h3 class='centered'>Результаты по выбранным категориям</h3>";
        }
        foreach ($songs as $song) {
            $song_id = $song->id;
            $song_name = $song->song_name;
            $total_song_emotions = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_emotions');
            $total_song_fans = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_fans');
            $html .= "<div class='one-song col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                <div class='row'>
                    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png'>
                        </span>
                    </div>
                    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                        <div class='actions'>
                            <span class='emotions_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                                <span class='badge' onclick='getSongEmotions(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongEmotions'>$total_song_emotions</span>
                            </span>
                            <span class='fans_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                                <span class='badge' onclick='getSongFans(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongFans'>$total_song_fans</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='song-name'>
                    <a href='" . base_url() . "one_song/$song_id'>
                        $song->song_singer - $song->song_name
                    </a>
                </div>
                <audio class='player' src='" . base_url() . "uploads/song_files/$song->song_file' controls controlsList='nodownload'></audio>
            </div>";
        }
        $data = array(
            'songs_by_categories' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
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