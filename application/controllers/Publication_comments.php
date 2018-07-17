<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $publication_id = $this->input->post('publication_id');
        $published_user_id = $this->input->post('published_user_id');
        $publication_comments = $this->publications_model->getPublicationCommentsByPublicationId($publication_id);
        $session_user_id = $_SESSION['user_id'];
        $session_user_email = $_SESSION['user_email'];
        $html = '';
        $csrf_hash = $this->security->get_csrf_hash();
        $html .= "<form action='javascript:void(0)' onsubmit='insertPublicationComment(this)'>
                    <input type='hidden' class='csrf' name='csrf_test_name' value='$csrf_hash'>
                    <textarea id='comment_text' class='form-control comment-input' placeholder='Добавить коммент' name='comment_text'></textarea>
                    <input class='published_user_id' type='hidden' name='published_user_id' value='$published_user_id'>
                    <input class='commented_user_id' type='hidden' name='commented_user_id' value='$session_user_id'>
                    <input class='publication_id' type='hidden' name='publication_id' value='$publication_id'>
                    <button class='btn btn-success center-block' type='submit'>Комментировать</button>
                  </form>
                  <div class='comments_by_publication'>";
                    if (count($publication_comments) > 0) {
                        foreach ($publication_comments as $publication_comment) {
                            $html .= "<div class='one_comment_$publication_comment->id'>
                                        <div class='commented_user'>
                                            <a href='" . base_url() . "one_user/$publication_comment->email'>
                                                <img src='" . base_url() . "uploads/images/user_images/" . $publication_comment->main_image . "' class='commented_avatar'>
                                                $publication_comment->nickname $publication_comment->surname 
                                            </a>
                                            <span class='comment-date-time'>$publication_comment->comment_date <br> $publication_comment->comment_time</span>";
                            if ($publication_comment->email == $session_user_email) {
                                $html .= "<div onclick='deletePublicationComment(this)' data-publication_comment_id='$publication_comment->id' data-publication_id='$publication_id' class='right'>X</div>";
                            }
                            $html .= "</div>
                                        <div class='comment_text'>
                                           $publication_comment->comment_text
                                        </div>
                                    </div>";
                        }
                    }
            $html .= "</div>";
            $get_comments_json = array(
                'one_publication_comments' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            echo json_encode($get_comments_json);
    }

    public function insert_publication_comment() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $published_user_id = $this->input->post('published_user_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $publication_id = $this->input->post('publication_id');

        if ($comment_text != '' && $commented_user_id == $session_user_id) {
            $data_publication_comments = array(
                'comment_text' => $comment_text,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'published_user_id' => $published_user_id,
                'commented_user_id' => $commented_user_id,
                'publication_id' => $publication_id
            );
            $this->publications_model->insertPublicationComment($data_publication_comments);

            $insert_comment_id = $this->db->insert_id();

            $total_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
            $user_email = $this->users_model->getEmailById($commented_user_id);
            $user_name = $this->users_model->getNicknameAndSurnameById($commented_user_id);
            $user_image = $this->users_model->getMainImageById($commented_user_id);

            $insert_json = array(
                'comment_id' => $insert_comment_id,
                'comment_date' => $comment_date,
                'comment_time' => $comment_time,
                'comment_text' => $comment_text,
                'total_comments' => $total_comments,
                'user_email' => $user_email,
                'user_name' => $user_name,
                'user_image' => $user_image,
                'publication_id' => $publication_id,
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

    public function delete_publication_comment() {
        $id = $this->input->post('id');
        $publication_id = $this->input->post('publication_id');
        $session_user_id = $_SESSION['user_id'];
        $comment_num_rows = $this->publications_model->getPublicationCommentNumRowsByIdAndCommentedUserId($id, $session_user_id);
        if ($comment_num_rows > 0) {
            $this->publications_model->deletePublicationCommentById($id);
            $total_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
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

    public function update_publication_comment() {
        $id = $this->input->post('id');
        $comment_text = $this->input->post('comment_text');

        $data_publication_comments = array(
            'comment_text' => $comment_text
        );
        $this->publications_model->updatePublicationCommentById($id, $data_publication_comments);
    }
}