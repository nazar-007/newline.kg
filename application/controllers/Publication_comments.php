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

        $data_publication_comments = array(
            'comment_text' => $comment_text,
            'comment_date' => $comment_date,
            'comment_time' => $comment_time,
            'published_user_id' => $published_user_id,
            'commented_user_id' => $commented_user_id,
            'publication_id' => $publication_id
        );
        $this->publications_model->insertPublicationComment($data_publication_comments);

        if ($published_user_id != $commented_user_id) {
            $notification_text = 'Пользователь Назар прокомментировал Вашу публикацию.';
            $data_user_notifications = array(
                'notification_type' => 'Коммент на Вашу публикацию',
                'notification_text' => $notification_text,
                'notification_date' => $comment_date,
                'notification_time' => $comment_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $published_user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);
        }
    }

    public function delete_publication_comment() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationCommentById($id);
        $this->publications_model->deletePublicationCommentComplaintsByPublicationCommentId($id);
        $this->publications_model->deletePublicationCommentEmotionsByPublicationCommentId($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}