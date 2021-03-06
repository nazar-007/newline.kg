<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('albums_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = $_SESSION['user_id'];
        $data = array(
            'albums' => $this->albums_model->getAlbumsByUserId($user_id),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('albums', $data);
    }


    public function insert_album() {
        $album_name = $this->input->post('album_name');
        $session_user_id = $_SESSION['user_id'];
        $total_albums = $this->albums_model->getTotalAlbumsByUserId($session_user_id);

        if ($album_name == 'My Album' || $album_name == 'Publication Album') {
            $insert_json = array(
                'album_error' => "Название альбома совпадает с названием альбома по умолчанию. Придумайте другое название",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else if ($total_albums >= 4) {
            $insert_json = array(
                'album_error' => "Вы не можете создавать больше 4 альбомов",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_albums = array(
                'album_name' => $album_name,
                'user_id' => $session_user_id
            );
            $this->albums_model->insertAlbum($data_albums);
            $insert_id = $this->db->insert_id();
            $insert_json = array(
                'id' => $insert_id,
                'album_name' => $album_name,
                'album_success' => "Альбом успешно создан",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_album() {
        $id = $this->input->post('id');
        $album_name = $this->input->post('album_name');
        if ($album_name != 'User Album' || $album_name != 'Publication Album') {
            $user_images = $this->users_model->getUserImagesByAlbumId($id);
            if (count($user_images) > 0) {
                foreach ($user_images as $user_image) {
                    $user_image_id = $user_image->id;
                    $this->users_model->deleteUserImageActionsByUserImageId($user_image_id);
                    $this->users_model->deleteUserImageEmotionsByUserImageId($user_image_id);
                    $user_image_file = $this->users_model->getUserImageFileById($user_image_id);
                    unlink("./uploads/images/user_images/$user_image_file");
                }
            }
            $this->users_model->deleteUserImagesByAlbumId($id);
            $this->albums_model->deleteAlbumById($id);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'album_error' => "Не удалось удалить альбом",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function update_album() {
        $id = $this->input->post('id');
        $album_name = $this->input->post('album_name');
        $data_albums = array(
            'album_name' => $album_name
        );
        if ($album_name != 'User Album' || $album_name != 'Publication Album') {
            $this->albums_model->updateAlbumById($id, $data_albums);
            $update_json = array(
                'id' => $id,
                'album_name' => $album_name,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $update_json = array(
                'album_error' => 'Не удалось обновить название альбома',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($update_json);

    }

}