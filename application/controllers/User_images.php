<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_images extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('albums_model');
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
        if ($album_num_rows == 1 && $album_name == 'Publication Album') {
            redirect(base_url() . "publication_images/$album_id");
        } else if ($album_num_rows == 1) {
            $html = '';
            if ($album_user_id == $session_user_id) {
                $html .= "<button data-toggle='modal' data-target='#insertUserImage' class='btn btn-success center-block absolute'>Добавить фотографии</button>";
            }
            $html .= "<h3 class='centered'>Фотки альбома $album_name</h3>";
            $user_images = $this->users_model->getUserImagesByAlbumId($album_id);
            if (count($user_images) == 0) {
                $html .= "<h5 class='centered'>В данном альбоме фоток пока нет.</h5>";
            } else {
                if (count($user_images) > 0) {
                    $html .= "<div id='carousel' class='carousel slide' data-interval='false' data-ride='carousel'>
                            <div class='carousel-inner'>";
                    foreach ($user_images as $key => $user_image) {
                        $user_image_id = $user_image->id;
                        $user_image_file = $user_image->user_image_file;
                        $user_id = $user_image->user_id;
                        $image_emotion_num_rows = $this->users_model->getUserImageEmotionNumRowsByUserImageIdAndEmotionedUserId($user_image_id, $session_user_id);
                        $total_user_image_emotions = $this->users_model->getTotalByUserImageIdAndUserImageTable($user_image_id, 'user_image_emotions');
                        if ($key == 0) {
                            $html .= "<div class='item active'>";
                                if ($album_user_id == $session_user_id && $album_name == 'My Album') {
                                    $html .= "<div data-album_id='$album_id' data-image_file='$user_image_file' class='main_image' onclick='changeMainImage(this)'>Сделать главной</div>";
                                }
                                if ($album_user_id == $session_user_id) {
                                    $html .= "<div class='delete_image second_album'>
                                    <button onclick='deleteUserImagePress(this)' data-toggle='modal' data-target='#deleteUserImage' data-id='$user_image_id' data-album_id='$album_id' data-image_file='$user_image_file' class='btn btn-danger'>
                                        <span class='glyphicon glyphicon-trash'></span>
                                    </button>
                                </div>";
                                }
                                    $html .= "<img src='" . base_url() . "uploads/images/user_images/$user_image_file' class='user_images' style='width: initial; margin: 0 auto;'>
                                    <div class='user_image_emotion image_emotions_field_$user_image_id' data-user_id='$user_id' data-emotioned_user_id='$session_user_id' data-user_image_id='$user_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertUserImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deleteUserImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getUserImageEmotions(this)' data-toggle='modal' data-target='#getUserImageEmotions'>$total_user_image_emotions</span>
                                            </div>
                                        </div>";
                        } else {
                            $html .= "<div class='item'>";
                            if ($album_user_id == $session_user_id && $album_name == 'My Album') {
                                $html .= "<div data-album_id='$album_id' data-image_file='$user_image_file' class='main_image' onclick='changeMainImage(this)'>Сделать главной</div>";
                            }
                            if ($album_user_id == $session_user_id) {
                                $html .= "<div class='delete_image second_album'>
                                    <button onclick='deleteUserImagePress(this)' data-toggle='modal' data-target='#deleteUserImage' data-id='$user_image_id' data-album_id='$album_id' data-image_file='$user_image_file' class='btn btn-danger'>
                                        <span class='glyphicon glyphicon-trash'></span>
                                    </button>
                                </div>";
                            }
                            $html .= "<img src='" . base_url() . "uploads/images/user_images/$user_image_file' class='user_images' style='width: initial; margin: 0 auto'>
                                    <div class='user_image_emotion image_emotions_field_$user_image_id' data-user_id='$user_id' data-emotioned_user_id='$session_user_id' data-user_image_id='$user_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertUserImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deleteUserImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getUserImageEmotions(this)' data-toggle='modal' data-target='#getUserImageEmotions'>$total_user_image_emotions</span>
                                            </div>
                                        </div>";
                        }
                    }
                    if (count($user_images) > 1) {
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
                'current_id' => $album_id,
                'images' => $html,
                'album_num_rows' => $album_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );

            $this->load->view('user_images', $data);
        } else {
            $data_users = array(
                'album_num_rows' => $album_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('user_images', $data_users);
        }
    }

    public function change_main_image() {
        $id = $_SESSION['user_id'];
        $album_id = $this->input->post('album_id');
        $user_image_file = $this->input->post('user_image_file');
        $user_image_num_rows = $this->users_model->getUserImageNumRowsByAlbumIdAndUserImageFile($album_id, $user_image_file);
        if ($user_image_num_rows > 0) {
            $data = array(
                'main_image' => $user_image_file
            );
            $this->users_model->updateUserById($id, $data);

            $update_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'image_success' => 'Фотка поставлена на главную'
            );
        } else {
            $update_json = array(
                'csrf_hash' => $this->security->get_csrf_hash(),
                'image_error' => 'Не удалось обновить основную фотку'
            );
        }
        echo json_encode($update_json);
    }

    public function insert_user_image() {
        $user_image_date = date('d.m.Y');
        $user_image_time = date('H:i:s');

        $album_id = $this->input->post('album_id');
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        $count_files = count($_FILES['user_image']['name']);
        $files = $_FILES;
        $upload_images = array();
        $album_num_rows = $this->albums_model->getAlbumNumRowsById($album_id);
        $album_user_id = $this->albums_model->getUserIdByAlbumId($album_id);

        if ($user_id != $session_user_id || $album_num_rows == 0 || $album_user_id != $session_user_id) {
            $insert_json = array(
                'images_error' => "Не удалось добавить фотографии. Возможно, альбом не существует или Вы пытаетесь это в альбоме другого пользователя",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            if ($_FILES['user_image']['name'][0] != '') {
                for ($i = 0; $i < $count_files; $i++) {
                    $_FILES['user_image']['name'] = $files['user_image']['name'][$i];
                    $_FILES['user_image']['type'] = $files['user_image']['type'][$i];
                    $_FILES['user_image']['tmp_name'] = $files['user_image']['tmp_name'][$i];
                    $_FILES['user_image']['error'] = $files['user_image']['error'][$i];
                    $_FILES['user_image']['size'] = $files['user_image']['size'][$i];

                    $config['upload_path'] = './uploads/images/user_images';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['encrypt_name'] = true;
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('user_image')) {
                        $file_name = $this->upload->data()['file_name'];
                        $upload_images[] = $file_name;
                    }
                }
                $this->load->library('image_lib');

                foreach ($upload_images as $upload_image) {
                    $data_user_images = array(
                        'user_image_file' => $upload_image,
                        'user_image_date' => $user_image_date,
                        'user_image_time' => $user_image_time,
                        'album_id' => $album_id,
                        'user_id' => $user_id
                    );
                    $this->users_model->insertUserImage($data_user_images);
                    $insert_user_image_id = $this->db->insert_id();
                }
            }
            $insert_json = array(
                'images_success' => "Фотки добавлены!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_user_image() {
        $id = $this->input->post('id');
        $album_id = $this->input->post('album_id');
        $user_id = $this->input->post('user_id');
        $user_image_file = $this->input->post('user_image_file');
        $db_user_image_file = $this->users_model->getUserImageFileById($id);
        $session_user_id = $_SESSION['user_id'];

        $user_image_num_rows = $this->users_model->getUserImageNumRowsByAlbumIdAndUserImageFile($album_id, $user_image_file);
        if ($user_image_num_rows > 0 && $user_id == $session_user_id && $user_image_file == $db_user_image_file) {
            unlink("./uploads/images/user_images/$user_image_file");

            $this->users_model->deleteUserImageActionsByUserImageId($id);
            $this->users_model->deleteUserImageEmotionsByUserImageId($id);
            $this->users_model->deleteUserImageById($id);

            $main_image = $this->users_model->getMainImageById($user_id);

            if ($main_image == $user_image_file) {
                $data_users = array(
                    'main_image' => 'default.jpg'
                );
                $this->users_model->updateUserById($user_id, $data_users);
            }
            $delete_json = array(
                'image_success' => 'Фотка успешно удалена',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'image_error' => 'НЕ УДАЛОСЬ!',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}