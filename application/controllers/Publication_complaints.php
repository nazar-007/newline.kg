<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'publication' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ публикации.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Жалобщик</td>
                    <td>Текст жалобы</td>
                    <td>Проверка поста</td>
                    <td>Удалить жалобу</td>
                    <td>Принять жалобу</td>
                </tr>";

            $publication_complaints = $this->publications_model->getPublicationComplaintsByAdminId($admin_id);

            foreach ($publication_complaints as $publication_complaint) {
                $id = $publication_complaint->id;
                $email = $publication_complaint->email;
                $complaint_text = $publication_complaint->complaint_text;
                $publication_id = $publication_complaint->publication_id;
                $publication_name = $publication_complaint->publication_name;
                $html .= "<tr class='one-complaint-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>$complaint_text</td>
                        <td>
                            <button onclick='getOnePublicationByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOnePublication' data-id='$publication_id'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressPublicationComplaint(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deletePublicationComplaint' data-complaint_id='$id' data-complaint_text='$complaint_text' data-publication_name='$publication_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressPublicationComplaintAndDeletePressPublication(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deletePublicationComplaintAndPublication' data-complaint_id='$id' data-publication_id='$publication_id' data-complaint_text='$complaint_text' data-publication_name='$publication_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'publication_complaints' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data = array(
                'complaint_error' => 'У вас нет прав на просмотр жалоб на книги',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($data);
    }

    public function insert_publication_complaint() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('publications');
        $published_user_id = $this->input->post('published_user_id');
        $publication_id = $this->input->post('publication_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $complained_user_id);

        if ($published_user_id == $session_user_id) {
            $insert_json = array(
                'complaint_error' => "Вы не можете жаловаться на свою публикацию!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
                $data_publication_complaints = array(
                    'complaint_text' => $complaint_text,
                    'complaint_time_unix' => $complaint_time_unix,
                    'admin_id' => $admin_id,
                    'published_user_id' => $published_user_id,
                    'publication_id' => $publication_id,
                    'complained_user_id' => $complained_user_id
                );
                $this->publications_model->insertPublicationComplaint($data_publication_complaints);
                $insert_json = array(
                    'complaint_text' => $complaint_text,
                    'complaint_num_rows' => $complaint_num_rows,
                    'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                    'publication_id' => $publication_id,
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            } else {
                $insert_json = array(
                    'complaint_num_rows' => $complaint_num_rows,
                    'complaint_text' => $complaint_text,
                    'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную публикацию, или текст жалобы пуст, или что-то пошло не так.",
                    'publication_id' => $publication_id,
                    'csrf_hash' => $this->security->get_csrf_hash()
                );
            }
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_complaint_by_admin() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationComplaintById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_publication_complaint_by_admin() {
        $id = $this->input->post('id');
        $admin_table = $this->input->post('admin_table');
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable($admin_table);

        $data_publication_complaints = array(
            'complaint_time_unix' => time(),
            'admin_id' => $admin_id
        );
        $this->publications_model->updatePublicationComplaintById($id, $data_publication_complaints);
    }
}