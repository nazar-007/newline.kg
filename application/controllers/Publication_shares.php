<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_shares extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $this->load->view('session_user');

        $publication_id = $this->input->post('publication_id');
        $publication_shares = $this->publications_model->getPublicationSharesByPublicationId($publication_id);
        $html = '';
        $html .= "<div class='row'>";
        if (count($publication_shares) == 0) {
            $html .= "<h3 class='centered'>Пока никто не делился этой публикацией.</h3>";
        } else {
            foreach ($publication_shares as $publication_share) {
                $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 share_user'>
                        <a href='" . base_url() . "one_user/$publication_share->email'>
                            <div class='share_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$publication_share->main_image' class='action_avatar' style='width: 100px;'>
                            </div>
                            <div class='share_user_name'>
                                $publication_share->nickname $publication_share->surname
                            </div>
                        </a>
                    </div>";
            }
        }
        $html .= "</div>";
        $get_shares_json = array(
            'one_publication_shares' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_shares_json);
    }

    public function User_publication_shares() {
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        $html = '';

        $publication_shares = $this->publications_model->getPublicationSharesBySharedUserId($user_id);

        if (count($publication_shares) == 0) {
            $html .= "<h3 class='centered'>Пока нет репостов.</h3>";
        } else {
            foreach ($publication_shares as $publication_share) {
                $publication_id = $publication_share->publication_id;
                $published_user_id = $publication_share->published_user_id;
                $published_user_name = $this->users_model->getNicknameAndSurnameById($published_user_id);
                $published_user_email = $this->users_model->getEmailById($published_user_id);
                $published_user_image = $this->users_model->getMainImageById($published_user_id);
                $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);

                $html .= "<div class='one_publication'>
                    <div class='publication'>
                            <div class='bout_user'>
                                <a class='user_name' href='" . base_url() . "one_user/$published_user_email'>
                                    <img src='" . base_url() . "uploads/icons/shared.png'>" .
                                        $published_user_name . "
                                    <img src='" . base_url() . "uploads/images/user_images/" . $published_user_image . "' class='user_avatar'>
                                </a>
                                <span class='publication-date-time'>$publication_share->publication_date<br>$publication_share->publication_time</span>
                            </div>
                        <div class='user_publication'>
                            <h4 class='publication_name'>$publication_share->publication_name</h4>
                            <div class='publication_description'>" .
                    $publication_share->publication_description . "
                            </div>
                        </div>";
                if (count($publication_images) > 0) {
                    $html .= "<div id='carousel_$publication_id' class='carousel slide' data-interval='false' data-ride='carousel'>
                            <div class='carousel-inner'>";
                    foreach ($publication_images as $key => $publication_image) {
                        if ($key == 0) {
                            $html .= "<div class='item active'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto;'>
                                        </div>";
                        } else {
                            $html .= "<div class='item'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto'>
                                        </div>";
                        }
                    }
                    if (count($publication_images) > 1) {
                        $html .= "<a class='left carousel-control' href='#carousel_$publication_id' data-slide='prev'>
                              <span class='glyphicon glyphicon-chevron-left'></span>
                              <span class='sr-only'>Previous</span>
                            </a>
                            <a class='right carousel-control' href='#carousel_$publication_id' data-slide='next'>
                              <span class='glyphicon glyphicon-chevron-right'></span>
                              <span class='sr-only'>Next</span>
                            </a>";
                    }
                    $html .= "</div>
                    </div>";
                }
                $html .= "</div></div>";
            }
        }
        $data = array(
            'user_publication_shares' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }
    public function insert_publication_share() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $share_date = date("d.m.Y");
        $share_time = date("H:i:s");
        $published_user_id = $this->input->post('published_user_id');
        $shared_user_id = $this->input->post('shared_user_id');
        $publication_id = $this->input->post('publication_id');

        $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $shared_user_id);

        if ($published_user_id == $session_user_id) {
            $insert_json = array(
                'share_error' => "Вы не можете делиться своей публикацией!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            if ($share_num_rows == 0 && $shared_user_id == $session_user_id) {
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
                    'link_id' => $publication_id,
                    'link_table' => 'publications',
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
                    'share_error' => "Вы уже делились данной публикацией или что-то пошло не так!",
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            }
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_share() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $publication_id = $this->input->post('publication_id');
        $shared_user_id = $this->input->post('shared_user_id');
        $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $shared_user_id);
        if ($share_num_rows > 0 && $shared_user_id == $session_user_id) {
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
                'share_error' => "Вы ещё не репостили данную публикацию или что-то пошло не так!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }
}