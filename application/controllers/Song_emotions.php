<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $song_id = $this->input->post('song_id');
        $song_emotions = $this->songs_model->getSongEmotionsBySongId($song_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($song_emotions) == 0 ) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($song_emotions as $song_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$song_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$song_emotion->main_image' class='action_avatar'>
                            </div>
                            <div class='emotion_user_name'>
                                $song_emotion->nickname $song_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_song_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }

    public function insert_song_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $song_id = $this->input->post('song_id');
        $emotion_num_rows = $this->songs_model->getSongEmotionNumRowsBySongIdAndEmotionedUserId($song_id, $emotioned_user_id);

        if ($emotion_num_rows == 0 && $emotioned_user_id == $session_user_id) {
            $data_song_emotions = array(
                'emotion_date' => $emotion_date,
                'emotion_time' => $emotion_time,
                'emotioned_user_id' => $emotioned_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongEmotion($data_song_emotions);

            $user_name = $this->users_model->getNicknameAndSurnameById($emotioned_user_id);
            $song_name = $this->songs_model->getSongNameById($song_id);

            $data_song_actions = array(
                'song_action' => "$user_name поставил(-а) эмоцию на песню '$song_name'",
                'song_time_unix' => time(),
                'action_user_id' => $emotioned_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongAction($data_song_actions);

            $total_emotions = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_emotions');
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы уже ставили эмоцию на данную песню или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_song_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $song_id = $this->input->post('song_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->songs_model->getSongEmotionNumRowsBySongIdAndEmotionedUserId($song_id, $emotioned_user_id);

        if ($emotion_num_rows > 0 && $emotioned_user_id == $session_user_id) {
            $this->songs_model->deleteSongEmotionBySongIdAndEmotionedUserId($song_id, $emotioned_user_id);
            $total_emotions = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_emotions');
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на данную песню или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}