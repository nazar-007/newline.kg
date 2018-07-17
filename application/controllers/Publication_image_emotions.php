<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_image_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $this->load->view('session_user');

        $publication_image_id = $this->input->post('publication_image_id');
        $publication_image_emotions = $this->publications_model->getPublicationImageEmotionsByPublicationImageId($publication_image_id);
        $html = '';
        $html .= "<div class='row'>";
        foreach ($publication_image_emotions as $publication_image_emotion) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$publication_image_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$publication_image_emotion->main_image' class='action_avatar' style='width: 100px;'>
                            </div>
                            <div class='emotion_user_name'>
                                $publication_image_emotion->nickname $publication_image_emotion->surname
                            </div>
                        </a>
                    </div>";
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_publication_image_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }


    public function insert_publication_image_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_image_id = $this->input->post('publication_image_id');
        $emotion_num_rows = $this->publications_model->getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $emotioned_user_id);

        if ($emotion_num_rows == 0 && $emotioned_user_id == $session_user_id) {
            $data_publication_image_emotions = array(
                'emotion_date' => $emotion_date,
                'emotion_time' => $emotion_time,
                'emotioned_user_id' => $emotioned_user_id,
                'publication_image_id' => $publication_image_id
            );
            $this->publications_model->insertPublicationImageEmotion($data_publication_image_emotions);

            $total_emotions = $this->publications_model->getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, 'publication_image_emotions');
            $insert_json = array(
                'image_emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'image_emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы уже ставили эмоцию на данную фотку публикации или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_image_emotion() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $publication_image_id = $this->input->post('publication_image_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $emotion_num_rows = $this->publications_model->getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $emotioned_user_id);

        if ($emotion_num_rows > 0 && $emotioned_user_id == $session_user_id) {
            $this->publications_model->deletePublicationImageEmotionByPublicationImageIdAndEmotionedUserId($publication_image_id, $emotioned_user_id);
            $total_emotions = $this->publications_model->getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, 'publication_image_emotions');
            $delete_json = array(
                'image_emotion_num_rows' => $emotion_num_rows,
                'total_emotions' => $total_emotions,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'image_emotion_num_rows' => $emotion_num_rows,
                'emotion_error' => "Вы ещё не ставили эмоцию на данную фотку публикации или что-то пошло не так",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}