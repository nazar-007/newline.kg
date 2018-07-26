<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $song_id = $this->input->post('song_id');
        $song_fans = $this->songs_model->getSongFansBySongId($song_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($song_fans) == 0) {
            $html .= "<h3 class='centered'>Пока никто не добавлял это в любимки.</h3>";
        } else {
            foreach ($song_fans as $song_fan) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 fan_user'>
                        <a href='" . base_url() . "one_user/$song_fan->email'>
                            <div class='fan_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$song_fan->main_image' class='action_avatar'>
                            </div>
                            <div class='fan_user_name'>
                                $song_fan->nickname $song_fan->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_fans_json = array(
            'one_song_fans' => $html,
            'my_fan_songs' => $this->songs_model->getSongFansByFanUserId($session_user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_fans_json);
    }

    public function common_songs() {
        $this->load->view('session_user');
        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');
        $common_songs = $this->songs_model->getCommonSongsByTwoUsers($user_id, $friend_id);
        $html = '';
        foreach ($common_songs as $common_song) {
            $html.= "<div><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png'>
                        </span>
                    </div></div>
                    <div class='song-name'>
                        <a href='" . base_url() . "one_song/$common_song->song_id'>
                            $common_song->song_singer - $common_song->song_name
                        </a>
                    </div>
                    <audio class='player' src='" . base_url() . "uploads/song_files/$common_song->song_file' controls controlsList='nodownload'></audio>";
        }
        $get_common_json = array(
            'common_songs' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_common_json);

    }

    public function insert_song_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $fan_date = date("d.m.Y");
        $fan_time = date("H:i:s");
        $fan_user_id = $this->input->post('fan_user_id');
        $song_id = $this->input->post('song_id');

        $fan_num_rows = $this->songs_model->getSongFanNumRowsBySongIdAndFanUserId($song_id, $fan_user_id);

        if ($fan_num_rows == 0 && $fan_user_id == $session_user_id) {
            $data_song_fans = array(
                'fan_date' => $fan_date,
                'fan_time' => $fan_time,
                'fan_user_id' => $fan_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongFan($data_song_fans);

            $user_name = $this->users_model->getNicknameAndSurnameById($fan_user_id);
            $song_name = $this->songs_model->getSongNameById($song_id);

            $data_song_actions = array(
                'song_action' => "$user_name добавил в свои любимки песню '$song_name'",
                'song_time_unix' => time(),
                'action_user_id' => $fan_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongAction($data_song_actions);

            $total_fans = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_fans');
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы уже добавляли эту песню в любимки или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_song_fan() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $song_id = $this->input->post('song_id');
        $fan_user_id = $this->input->post('fan_user_id');
        $fan_num_rows = $this->songs_model->getSongFanNumRowsBySongIdAndFanUserId($song_id, $fan_user_id);

        if ($fan_num_rows > 0 && $fan_user_id == $session_user_id) {
            $this->songs_model->deleteSongFanBySongIdAndFanUserId($song_id, $fan_user_id);
            $total_fans = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_fans');
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'total_fans' => $total_fans,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'fan_num_rows' => $fan_num_rows,
                'fan_error' => "Вы ещё не добавляли данную книгу в любимки или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}