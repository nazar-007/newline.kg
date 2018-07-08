<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_images extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function Index() {
        $user_id = 1;
        $data = array(
            'albums' => $this->albums_model->getAlbums($user_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('albums', $data);
    }

    public function insert_user_image() {
        $album_id = $this->input->post('album_id');
        $user_id = $this->input->post('user_id');
        $count_files = count($_FILES['user_image']['name']);
        $files = $_FILES;
        $upload_images = array();

        for ($i = 0; $i < $count_files; $i++) {
            $_FILES['user_image']['name'] = $files['user_image']['name'][$i];
            $_FILES['user_image']['type'] = $files['user_image']['type'][$i];
            $_FILES['user_image']['tmp_name'] = $files['user_image']['tmp_name'][$i];
            $_FILES['user_image']['error'] = $files['user_image']['error'][$i];
            $_FILES['user_image']['size'] = $files['user_image']['size'][$i];

            $config['upload_path'] = './uploads/images/user_images';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|ico|svg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('user_image')) {
                $errors = array('error' => $this->upload->display_errors());
                echo 'не удалось загрузить файлы';
            } else {
                $file_name = $this->upload->data()['file_name'];
                $upload_images[] = $file_name;
            }
        }
        $this->load->library('image_lib');

        $user_image_date = date('d.m.Y');
        $user_image_time = date('H:i:s');

        foreach ($upload_images as $upload_image) {
            $config['image_library'] = 'gd2';
            $config['source_image'] = "./uploads/images/user_image/$upload_image";
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 100;
            $config['height'] = 50;
            $config['new_image'] = "./uploads/images/user_image/thumb/$upload_image";
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $data_user_images = array(
                'user_image_file' => $upload_image,
                'user_image_date' => $user_image_date,
                'user_image_time' => $user_image_time,
                'album_id' => $album_id,
                'user_id' => $user_id
            );
            $this->users_model->insertUserImage($data_user_images);
        }

        $user_image_action = 'Назар добавил новые фотки в свой альбом.';
        $data_user_image_actions = array(
            'user_image_action' => $user_image_action,
            'user_image_time_unix' => time(),
            'action_user_id' => $user_id,
            'user_image_id' => $user_image_id
        );
        $this->users_model->insertUserImageAction($data_user_image_actions);
    }

    public function delete_user_image() {
        $id = $this->input->post('id');
        $user_image_file = $this->users_model->getUserImageFileById($id);
        unlink("./uploads/images/user_images/$user_image_file");
        unlink("./uploads/images/user_images/thumb/$user_image_file");

        $user_image_comments = $this->users_model->getUserImageCommentsByUserImageId($id);
        foreach ($user_image_comments as $user_image_comment) {
            $user_image_comment_id = $user_image_comment->id;
            $this->users_model->deleteUserImageCommentEmotionsByUserImageCommentId($user_image_comment_id);
        }
        $this->users_model->deleteUserImageActionsByUserImageId($id);
        $this->users_model->deleteUserImageCommentsByUserImageId($id);
        $this->users_model->deleteUserImageCommentEmotionsByUserImageId($id);
        $this->users_model->deleteUserImageEmotionsByUserImageId($id);
        $this->users_model->deleteUserImageById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}