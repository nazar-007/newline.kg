<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'song' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ песен.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Жалобщик</td>
                    <td>Текст жалобы</td>
                    <td>Проверка песни</td>
                    <td>Удалить жалобу</td>
                    <td>Принять жалобу</td>
                </tr>";

            $song_complaints = $this->songs_model->getSongComplaintsByAdminId($admin_id);

            foreach ($song_complaints as $song_complaint) {
                $id = $song_complaint->id;
                $email = $song_complaint->email;
                $complaint_text = $song_complaint->complaint_text;
                $song_id = $song_complaint->song_id;
                $song_name = $song_complaint->song_name;
                $html .= "<tr class='one-complaint-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>$complaint_text</td>
                        <td>
                            <button onclick='getOneSongByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneSong' data-id='$song_id'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressSongComplaint(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteSongComplaint' data-complaint_id='$id' data-complaint_text='$complaint_text' data-song_name='$song_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressSongComplaintAndDeletePressSong(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteSongComplaintAndSong' data-complaint_id='$id' data-song_id='$song_id' data-complaint_text='$complaint_text' data-song_name='$song_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'song_complaints' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'complaint_error' => 'У вас нет прав на просмотр жалоб на песни',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
    }

    public function insert_song_complaint() {
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('songs');
        $song_id = $this->input->post('song_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->songs_model->getSongComplaintNumRowsBySongIdAndComplainedUserId($song_id, $complained_user_id);
        if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
            $data_song_complaints = array(
                'complaint_text' => $complaint_text,
                'complaint_time_unix' => $complaint_time_unix,
                'admin_id' => $admin_id,
                'song_id' => $song_id,
                'complained_user_id' => $complained_user_id
            );
            $this->songs_model->insertSongComplaint($data_song_complaints);
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                'song_id' => $song_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную песню, или текст жалобы пуст, или что-то пошло не так.",
                'song_id' => $song_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_song_complaint() {
        $id = $this->input->post('id');
        $song_name = $this->input->post('song_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->songs_model->deleteSongComplaintById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил жалобу на песню '$song_name' с текстом $complaint_text под id $id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'complaint_error' => "Не удалось удалить жалобу.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_song_complaint_and_song() {
        $id = $this->input->post('id');
        $song_id = $this->input->post('song_id');
        $song_name = $this->input->post('song_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->songs_model->deleteSongComplaintsBySongId($song_id);

            $song_file = $this->songs_model->getSongFileById($song_id);
            unlink("./uploads/song_files/$song_file");

            $this->songs_model->deleteSongActionsBySongId($song_id);
            $this->songs_model->deleteSongCommentsBySongId($song_id);
            $this->songs_model->deleteSongComplaintsBySongId($song_id);
            $this->songs_model->deleteSongEmotionsBySongId($song_id);
            $this->songs_model->deleteSongFansBySongId($song_id);
            $this->songs_model->deleteSongById($song_id);

            $data_admin_actions = array(
                'admin_action' => "$admin_email принял жалобу на песню '$song_name' с текстом $complaint_text под id $id и удалил эту песню под id $song_id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'complaint_success' => 'Жалоба успешно принята',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'complaint_error' => "Не удалось удалить жалобу.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}