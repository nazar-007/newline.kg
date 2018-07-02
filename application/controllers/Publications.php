<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $friend_ids = array();
        $data = array(
            'publications' => $this->publications_model->getPublicationsByFriendIds($friend_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

    public function insert_publication() {
        $publication_name = $this->input->post('book_name');
        $publication_description = $this->input->post('book_file');
        $publication_date = date('d.m.Y');
        $publication_time = date('d.m.Y');
        $album_id = $this->input->post('album_id');
        $published_user_id = $this->input->post('published_user_id');

        $data_publication = array(
            'publication_name' => $publication_name,
            'publication_description' => $publication_description,
            'publication_date' => $publication_date,
            'publication_time' => $publication_time,
            'published_user_id' => $published_user_id
        );

        $this->publications_model->insertPublication($data_publication);
        $insert_publication_id = $this->db->insert_id();

        $count_files = count($_FILES['publication_image']['name']);
        $files = $_FILES;
        $upload_images = array();

        for ($i = 0; $i < $count_files; $i++) {
            $_FILES['publication_image']['name'] = $files['publication_image']['name'][$i];
            $_FILES['publication_image']['type'] = $files['publication_image']['type'][$i];
            $_FILES['publication_image']['tmp_name'] = $files['publication_image']['tmp_name'][$i];
            $_FILES['publication_image']['error'] = $files['publication_image']['error'][$i];
            $_FILES['publication_image']['size'] = $files['publication_image']['size'][$i];

            $config['upload_path'] = './uploads/images/publication_images';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|ico|svg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('publication_image')) {
                $errors = array('error' => $this->upload->display_errors());
                echo 'не удалось загрузить файлы';
            } else {
                $file_name = $this->upload->data()['file_name'];
                $upload_images[] = $file_name;
            }
        }
        $this->load->library('image_lib');

        foreach ($upload_images as $upload_image) {
            $config['image_library'] = 'gd2';
            $config['source_image'] = "./uploads/images/publication_images/$upload_image";
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 100;
            $config['height'] = 50;
            $config['new_image'] = "./uploads/images/publication_images/thumb/$upload_image";
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $data_publication_images = array(
                'publication_image_file' => $upload_image,
                'publication_image_date' => $publication_date,
                'publication_image_time' => $publication_time,
                'album_id' => $album_id,
                'publication_id' => $insert_publication_id
            );
            $this->publications_model->insertPublicationImage($data_publication_images);
        }
    }

    public function delete_publication_by_user() {
        $id = $this->input->post('id');
        $publication_comments = $this->publications_model->getPublicationCommentsByPublicationId($id);
        foreach ($publication_comments as $publication_comment) {
            $publication_comment_id = $publication_comment->id;
            $this->publications_model->deletePublicationCommentComplaintsByPublicationCommentId($publication_comment_id);
            $this->publications_model->deletePublicationCommentEmotionsByPublicationCommentId($publication_comment_id);
        }
        $publication_images = $this->publications_model->getPublicationImagesByPublicationId($id);
        foreach ($publication_images as $publication_image) {
            $publication_image_id = $publication_image->id;
            $publication_image_file = $this->publications_model->getPublicationImageFileById($publication_image_id);
            unlink("./uploads/images/publication_images/$publication_image_file");
            unlink("./uploads/images/publication_images/thumb/$publication_image_file");
            $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($publication_image_id);
        }
        $publication_shares = $this->publications_model->getPublicationSharesByPublicationId($id);
        foreach ($publication_shares as $publication_share) {
            $publication_share_id = $publication_share->id;
            $this->publications_model->deletePublicationShareEmotionsByPublicationShareId($publication_share_id);
        }

        $this->publications_model->deletePublicationCommentsByPublicationId($id);
        $this->publications_model->deletePublicationComplaintsByPublicationId($id);
        $this->publications_model->deletePublicationEmotionsByPublicationId($id);
        $this->publications_model->deletePublicationImagesByPublicationId($id);
        $this->publications_model->deletePublicationSharesByPublicationId($id);
        $this->publications_model->deletePublicationById($id);

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_publication_by_admin_without_ban() {
        $user_id = $this->input->post('user_id');
        $this->delete_publication_by_user();
        $notification_text = 'Админ удалил Вашу публикацию "Тема 18+".';

        $data_user_notifications = array(
            'notification_type' => 'Удаление Вашей публикации',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_publication_by_admin_with_ban() {

        $this->delete_publication_by_user();
        $user_id = $this->input->post('user_id');
        $notification_text = 'Админ забанил Ваш аккаунт из-за Вашей публикации "Тема 18+" и удалил её. Теперь Вы не можете добавлять публикации. Если Вы считаете, что бан произошёл несправедливо, сообщите об ошибке.';

        $data_users = array(
            'my_account_access' => "Закрыто"
        );
        $this->users_model->updateUserById($user_id, $data_users);

        $data_user_notifications = array(
            'notification_type' => 'Бан Вашего аккаунта',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function update_publication() {
        $id = $this->input->post('id');
        $publication_name = $this->input->post('book_name');
        $publication_description = $this->input->post('book_file');

        $data_publication = array(
            'publication_name' => $publication_name,
            'publication_description' => $publication_description
        );

        $this->publications_model->updatePublicationById($id, $data_publication);
    }

}