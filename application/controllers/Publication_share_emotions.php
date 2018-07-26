<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_share_emotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');

        $publication_share_id = $this->input->post('publication_share_id');
        $publication_share_emotions = $this->publications_model->getPublicationShareEmotionsByPublicationShareId($publication_share_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($publication_share_emotions) == 0) {
            $html .= "<h3 class='centered'>Пока никто не ставил эмоцию.</h3>";
        } else {
            foreach ($publication_share_emotions as $publication_share_emotion) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$publication_share_emotion->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$publication_share_emotion->main_image' class='action_avatar' style='width: 100px;'>
                            </div>
                            <div class='emotion_user_name'>
                                $publication_share_emotion->nickname $publication_share_emotion->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_emotions_json = array(
            'one_publication_image_emotions' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_emotions_json);
    }

    public function insert_publication_share_emotion() {
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $share_user_id = $this->input->post('share_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_share_id = $this->input->post('publication_share_id');

        $data_publication_share_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'share_user_id' => $share_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_share_id' => $publication_share_id,
        );
        $this->publications_model->insertPublicationShareEmotion($data_publication_share_emotions);
    }

    public function delete_publication_share_emotion() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationShareEmotionById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_publication_share_emotion() {
        $id = $this->input->post('id');
        $emotion_date = date('d.m.Y');
        $emotion_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $emotioned_user_id = $this->input->post('emotioned_user_id');
        $publication_id = $this->input->post('publication_id');
        $publication_share_id = $this->input->post('publication_share_id');
        $emotion_id = $this->input->post('emotion_id');

        $data_publication_image_emotions = array(
            'emotion_date' => $emotion_date,
            'emotion_time' => $emotion_time,
            'published_user_id' => $published_user_id,
            'emotioned_user_id' => $emotioned_user_id,
            'publication_id' => $publication_id,
            'publication_share_id' => $publication_share_id,
            'emotion_id' => $emotion_id
        );
        $this->publications_model->updatePublicationShareEmotionById($id, $data_publication_image_emotions);
    }
}