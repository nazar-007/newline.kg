<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_images extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('albums_model');
        $this->load->model('users_model');
        $this->load->model('publications_model');
    }

    public function Index($album_id) {
        $album_num_rows = $this->albums_model->getAlbumNumRowsById($album_id);
        $album_user_id = $this->albums_model->getUserIdByAlbumId($album_id);
        if ($album_num_rows == 1) {
            $album_name = $this->albums_model->getAlbumNameById($album_id);
        } else {
            $album_name = '';
        }
        $session_user_id = $_SESSION['user_id'];
        if ($album_num_rows == 1 && $album_name != 'Publication Album') {
            redirect(base_url() . "user_images/$album_id");
        } else if ($album_num_rows == 1) {
            $html = "<h3 class='centered'>Фотки альбома $album_name</h3>";
            $publication_images = $this->publications_model->getPublicationImagesByAlbumId($album_id);
            if (count($publication_images) == 0) {
                $html .= "<h5 class='centered'>В данном альбоме фоток пока нет.</h5>";
            } else {
                if (count($publication_images) > 0) {
                    $html .= "<div id='carousel' class='carousel slide' data-interval='false' data-ride='carousel'>
                            <div class='carousel-inner'>";
                    foreach ($publication_images as $key => $publication_image) {
                        $publication_image_id = $publication_image->id;
                        $publication_image_file = $publication_image->publication_image_file;
                        $image_emotion_num_rows = $this->publications_model->getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $session_user_id);
                        $total_publication_image_emotions = $this->publications_model->getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, 'publication_image_emotions');
                        if ($key == 0) {
                            $html .= "<div class='item active'>
                                                <img src='" . base_url() . "uploads/images/publication_images/$publication_image_file' class='publication_images' style='width: 200px; margin: 0 auto;'>
                                                <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                                </div>
                                            </div>";
                        } else {
                            $html .= "<div class='item'>
                                                <img src='" . base_url() . "uploads/images/publication_images/$publication_image_file' class='publication_images' style='width: 200px; margin: 0 auto'>
                                                <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                                </div>
                                            </div>";
                        }
                    }
                    if (count($publication_images) > 1) {
                        $html .= "<a class='left carousel-control' href='#carousel' data-slide='prev'>
                                  <span class='glyphicon glyphicon-chevron-left'></span>
                                  <span class='sr-only'>Previous</span>
                                </a>
                                <a class='right carousel-control' href='#carousel' data-slide='next'>
                                  <span class='glyphicon glyphicon-chevron-right'></span>
                                  <span class='sr-only'>Next</span>
                                </a>";
                    }
                    $html .= "</div>
                    </div>";

                }

            }
            $user_name = $this->users_model->getNicknameAndSurnameById($album_user_id);
            $user_albums = $this->albums_model->getAlbumsByUserId($album_user_id);

            $html .= "<h3 class='centered'>Все альбомы пользователя $user_name</h3>";

            foreach ($user_albums as $user_album) {
                $user_album_id = $user_album->id;
                $user_album_name = $user_album->album_name;
                $html .= "<div class='col-xs-6 col-sm-6 col-md-3 col-lg-3 one_album_$user_album_id'>
                        <a href='" . base_url() . "user_images/$user_album_id'>
                            <img src='" . base_url() . "uploads/icons/my_album.png'>
                            <div class='one_album_name_$user_album_id album_name'>
                                $user_album_name
                            </div>
                        </a>
                    </div>";
            }

            $data = array(
                'images' => $html,
                'album_num_rows' => $album_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );

            $this->load->view('publication_images', $data);
        } else {
            $data_users = array(
                'album_num_rows' => $album_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('publication_images', $data_users);
        }
    }

    public function delete_publication_image() {
        $id = $this->input->post('id');
        $publication_image_file = $this->publications_model->getPublicationImageFileById($id);
        unlink("./uploads/images/publication_images/$publication_image_file");
        $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($id);
        $this->publications_model->deletePublicationImageById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}