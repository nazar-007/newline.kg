<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function get_publication_emotions() {
        $publication_id = $this->input->post('publication_id');
        $publication_emotions = $this->publications_model->getPublicationEmotionsByPublicationId($publication_id);
        $html = '';
        $html .= "<div class='row'>";
        foreach ($publication_emotions as $publication_emotion) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$publication_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$publication_emotion->main_image' class='emotion_avatar' style='width: 100px;'>
                            </div>
                            <div class='emotion_user_name'>
                                $publication_emotion->nickname $publication_emotion->surname
                            </div>
                        </a>
                    </div>";
        }
        $html .= "</div>";
        $data = array(
            'publication_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_publication_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_id = $this->input->post('publication_id');
        $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $emotioned_user_id);

        if ($emotion_num_rows == 0) {
            $data_publication_emotions = array(
                'emotion_date' => $emotion_date,
                'emotion_time' => $emotion_time,
                'published_user_id' => $published_user_id,
                'emotioned_user_id' => $emotioned_user_id,
                'publication_id' => $publication_id,
            );
            $this->publications_model->insertPublicationEmotion($data_publication_emotions);

            $user_name = $this->users_model->getNicknameAndSurnameById($emotioned_user_id);

            $notification_text = "$user_name поставил эмоцию на Вашу публикацию.";

            $total_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
            $data_user_notifications = array(
                'notification_type' => 'Эмоция на Вашу публикацию',
                'notification_text' => $notification_text,
                'notification_date' => $emotion_date,
                'notification_time' => $emotion_time,
                'notification_viewed' => 'Не просмотрено',
                'user_id' => $published_user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы уже ставили эмоцию на данную публикацию",
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_emotion() {
        $publication_id = $this->input->post('publication_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $emotioned_user_id);
        if ($emotion_num_rows > 0) {
            $this->publications_model->deletePublicationEmotionByPublicationIdAndEmotionedUserId($publication_id, $emotioned_user_id);
            $total_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на данную публикацию",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}