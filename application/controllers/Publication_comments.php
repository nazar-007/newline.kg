<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_comments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $publication_id = 1;
        $data = array(
            'publication_comments' => $this->publications_model->getPublicationCommentsByPublicationId($publication_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_comments', $data);
    }

    public function insert_publication_comment() {
        $comment_text = $this->input->post('comment_text');
        $comment_date = date("d.m.Y");
        $comment_time = date("H:i:s");
        $published_user_id = $this->input->post('published_user_id');
        $commented_user_id = $this->input->post('commented_user_id');
        $publication_id = $this->input->post('publication_id');

        if ($comment_text != '') {
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
                'comment_error' => "Вы ввели пустой коммент!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_comment() {
        $id = $this->input->post('id');
        $publication_id = $this->input->post('publication_id');
        $this->publications_model->deletePublicationCommentById($id);
        $total_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
        $delete_json = array(
            'total_comments' => $total_comments,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
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