<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $song_id = $this->input->post('song_id');
        $song_comments = $this->songs_model->getSongCommentsBySongId($song_id);
        $session_user_id = $_SESSION['user_id'];
        $session_user_email = $_SESSION['user_email'];
        $html = '';
        $csrf_hash = $this->security->get_csrf_hash();
        $html .= "<form action='javascript:void(0)' onsubmit='insertSongComment(this)'>
                    <input type='hidden' class='csrf' name='csrf_test_name' value='$csrf_hash'>
                    <textarea required id='comment_text' class='form-control comment-input' placeholder='Добавить коммент' name='comment_text'></textarea>
                    <input class='commented_user_id' type='hidden' name='commented_user_id' value='$session_user_id'>
                    <input class='song_id' type='hidden' name='song_id' value='$song_id'>
                    <button class='btn btn-success center-block' type='submit'>Комментировать</button>
                  </form>
                  <div class='comments_by_song'>";
        if (count($song_comments) > 0) {
            foreach ($song_comments as $song_comment) {
                $html .= "<div class='one_comment_$song_comment->id'>
                            <div class='commented_user'>
                                <a href='" . base_url() . "one_user/$song_comment->email'>
                                    <img src='" . base_url() . "uploads/images/user_images/" . $song_comment->main_image . "' class='commented_avatar'>
                                    $song_comment->nickname $song_comment->surname 
                                </a>
                                <span class='comment-date-time'>$song_comment->comment_date <br> $song_comment->comment_time</span>";
                if ($song_comment->email == $session_user_email) {
                    $html .= "<div onclick='deleteSongComment(this)' data-song_comment_id='$song_comment->id' data-song_id='$song_id' class='right'>X</div>";
                }
                $html .= "</div>
                    <div class='comment_text'>
                       $song_comment->comment_text
                    </div>
                </div>";
            }
        }
        $html .= "</div>";
        $get_comments_json = array(
            'one_song_comments' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_comments_json);
    }

    public function insert_song_comment() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $commented_user_id = $this->input->post('commented_user_id');
        $song_id = $this->input->post('song_id');

        if ($comment_text != '' && $commented_user_id == $session_user_id) {
            $data_book_comments = array(
                'comment_text' => $comment_text,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'commented_user_id' => $commented_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongComment($data_book_comments);

            $insert_comment_id = $this->db->insert_id();

            $total_comments = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_comments');
            $user_email = $this->users_model->getEmailById($commented_user_id);
            $user_name = $this->users_model->getNicknameAndSurnameById($commented_user_id);
            $user_image = $this->users_model->getMainImageById($commented_user_id);
            $song_name = $this->songs_model->getSongNameById($song_id);

            $data_song_actions = array(
                'song_action' => "$user_name оставил(-а) коммент на песню '$song_name'",
                'song_time_unix' => time(),
                'action_user_id' => $commented_user_id,
                'song_id' => $song_id
            );
            $this->songs_model->insertSongAction($data_song_actions);

            $insert_json = array(
                'comment_id' => $insert_comment_id,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'comment_text' => $comment_text,
                'total_comments' => $total_comments,
                'user_email' => $user_email,
                'user_name' => $user_name,
                'user_image' => $user_image,
                'song_id' => $song_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'comment_text' => $comment_text,
                'comment_error' => "Вы ввели пустой коммент или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_song_comment() {
        $id = $this->input->post('id');
        $song_id = $this->input->post('song_id');
        $session_user_id = $_SESSION['user_id'];
        $comment_num_rows = $this->songs_model->getSongCommentNumRowsByIdAndCommentedUserId($id, $session_user_id);
        if ($comment_num_rows > 0) {
            $this->songs_model->deleteSongCommentById($id);
            $total_comments = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_comments');
            $delete_json = array(
                'total_comments' => $total_comments,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'comment_error' => "Не удалось удалить коммент или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }

        echo json_encode($delete_json);
    }


    public function delete_song_comment_by_admin() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] && $_SESSION['admin_table']) {
            $this->songs_model->deleteSongCommentById($id);

            $data_admin_actions = array(
                'admin_action' => "$admin_email удалил коммент с текстом $comment_text под id $id",
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
                'comment_error' => 'У вас нет прав на удаление комментов в песне',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function update_song_comment() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');

        $data_song_comments = array(
            'comment_text' => $comment_text
        );
        $this->songs_model->updateSongCommentById($id, $data_song_comments);
    }
}