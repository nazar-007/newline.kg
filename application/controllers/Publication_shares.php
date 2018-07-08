<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_shares extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $publication_id = 2;
        $data = array(
            'publication_shares' => $this->publications_model->getPublicationSharesByPublicationId($publication_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_shares', $data);
    }

    public function get_publication_shares() {
        $publication_id = $this->input->post('publication_id');
        $publication_shares = $this->publications_model->getPublicationSharesByPublicationId($publication_id);
        $html = '';
        $html .= "<div class='row'>";
        foreach ($publication_shares as $publication_share) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 share_user'>
                        <a href='" . base_url() . "one_user/$publication_share->email'>
                            <div class='share_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$publication_share->main_image' class='emotion_avatar' style='width: 100px;'>
                            </div>
                            <div class='share_user_name'>
                                $publication_share->nickname $publication_share->surname
                            </div>
                        </a>
                    </div>";
        }
        $html .= "</div>";
        $data = array(
            'publication_shares' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_publication_share() {
        $share_date = date("d.m.Y");
        $share_time = date("H:i:s");
        $published_user_id = $this->input->post('published_user_id');
        $shared_user_id = $this->input->post('shared_user_id');
        $publication_id = $this->input->post('publication_id');

        $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $shared_user_id);

        if ($share_num_rows == 0) {
        $data_publication_shares = array(
            'share_date' => $share_date,
            'share_time' => $share_time,
            'shared_user_id' => $shared_user_id,
            'publication_id' => $publication_id
        );
        $this->publications_model->insertPublicationShare($data_publication_shares);
        $user_name = $this->users_model->getNicknameAndSurnameById($shared_user_id);

        $notification_text = "$user_name поделился Вашей публикацией.";

        $total_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
        $data_user_notifications = array(
            'notification_type' => 'Репост Вашей публикации',
            'notification_text' => $notification_text,
            'notification_date' => $share_date,
            'notification_time' => $share_time,
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $published_user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
        $insert_json = array(
            'share_num_rows' => $share_num_rows,
            'total_shares' => $total_shares,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        } else {
            $insert_json = array(
                'share_num_rows' => $share_num_rows,
                'share_error' => "Вы уже делились данной публикацией!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_share() {
        $publication_id = $this->input->post('publication_id');
        $shared_user_id = $this->input->post('shared_user_id');
        $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $shared_user_id);
        if ($share_num_rows > 0) {
            $this->publications_model->deletePublicationShareByPublicationIdAndSharedUserId($publication_id, $shared_user_id);
            $total_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
            $delete_json = array(
                'share_num_rows' => $share_num_rows,
                'total_shares' => $total_shares,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'share_num_rows' => $share_num_rows,
                'emotion_error' => "Вы ещё не репостили данную публикацию!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}