<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_complaints extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
        $this->load->model('admins_model');
        $this->load->model('users_model');
    }

    public function Index() {
        $material = $this->input->post('material');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($material == 'book' && $admin_id && $admin_email && $admin_table) {

            $html = "<h3 class='centered'>Вы - админ книг.</h3>
            <table border='3'>
                <tr>
                    <td>ID</td>
                    <td>Жалобщик</td>
                    <td>Текст жалобы</td>
                    <td>Проверка книги</td>
                    <td>Удалить жалобу</td>
                    <td>Принять жалобу</td>
                </tr>";

            $book_complaints = $this->books_model->getBookComplaintsByAdminId($admin_id);

            foreach ($book_complaints as $book_complaint) {
                $id = $book_complaint->id;
                $email = $book_complaint->email;
                $complaint_text = $book_complaint->complaint_text;
                $book_id = $book_complaint->book_id;
                $book_name = $book_complaint->book_name;
                $html .= "<tr class='one-complaint-$id'>
                        <td>$id</td>
                        <td>$email</td>
                        <td>$complaint_text</td>
                        <td>
                            <button onclick='getOneBookByAdmin(this)' type='button' class='btn btn-default' data-toggle='modal' data-target='#getOneBook' data-id='$book_id'><span class='glyphicon glyphicon-align-justify'></span></button>
                        </td> 
                        <td>
                            <button onclick='deletePressBookComplaint(this)' type='button' class='btn btn-success' data-toggle='modal' data-target='#deleteBookComplaint' data-complaint_id='$id' data-complaint_text='$complaint_text' data-book_name='$book_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                        <td>
                            <button onclick='deletePressBookComplaintAndDeletePressBook(this)' type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteBookComplaintAndBook' data-complaint_id='$id' data-book_id='$book_id' data-complaint_text='$complaint_text' data-book_name='$book_name'><span class='glyphicon glyphicon-trash'></span></button>
                        </td>
                    </tr>";
            }

            $html .= "</table>";

            $data = array(
                'book_complaints' => $html,
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

    public function insert_book_complaint() {
        $session_user_id = $_SESSION['user_id'];
        $complaint_text = $this->input->post('complaint_text');
        $complaint_time_unix = time();
        $admin_id = $this->admins_model->getRandomAdminIdByAdminTable('books');
        $book_id = $this->input->post('book_id');
        $complained_user_id = $this->input->post('complained_user_id');

        $complaint_num_rows = $this->books_model->getBookComplaintNumRowsByBookIdAndComplainedUserId($book_id, $complained_user_id);
        if ($complaint_num_rows == 0 && $complaint_text != '' && $complained_user_id == $session_user_id) {
            $data_book_complaints = array(
                'complaint_text' => $complaint_text,
                'complaint_time_unix' => $complaint_time_unix,
                'admin_id' => $admin_id,
                'book_id' => $book_id,
                'complained_user_id' => $complained_user_id
            );
            $this->books_model->insertBookComplaint($data_book_complaints);
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_success' => "Ваша жалоба отправлена и будет рассмотрена при первой же возможности!",
                'book_id' => $book_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'complaint_text' => $complaint_text,
                'complaint_num_rows' => $complaint_num_rows,
                'complaint_error' => "Невозможно отправить жалобу. Вы уже жаловались на данную книгу, или текст жалобы пуст, или что-то пошло не так.",
                'book_id' => $book_id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_book_complaint() {
        $id = $this->input->post('id');
        $book_name = $this->input->post('book_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->books_model->deleteBookComplaintById($id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email отклонил жалобу на книгу '$book_name' с текстом $complaint_text под id $id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'complaint_error' => "Не удалось удалить жалобу.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

    public function delete_book_complaint_and_book() {
        $id = $this->input->post('id');
        $book_id = $this->input->post('book_id');
        $book_name = $this->input->post('book_name');
        $complaint_text = $this->input->post('complaint_text');

        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];
        if ($admin_id && $admin_email && $admin_table) {
            $this->books_model->deleteBookComplaintsByBookId($book_id);

            $book_file = $this->books_model->getBookFileById($book_id);
            $book_image = $this->books_model->getBookImageById($book_id);
            unlink("./uploads/book_files/$book_file");
            unlink("./uploads/images/book_images/$book_image");

            $this->books_model->deleteBookActionsByBookId($book_id);
            $this->books_model->deleteBookCommentsByBookId($book_id);
            $this->books_model->deleteBookComplaintsByBookId($book_id);
            $this->books_model->deleteBookEmotionsByBookId($book_id);
            $this->books_model->deleteBookFansByBookId($book_id);
            $this->books_model->deleteBookById($book_id);
            $data_admin_actions = array(
                'admin_action' => "$admin_email принял жалобу на книгу '$book_name' с текстом $complaint_text под id $id и удалил эту книгу под id $book_id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
            $delete_json = array(
                'id' => $id,
                'complaint_success' => 'Жалоба успешно принята',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'complaint_error' => "Не удалось удалить жалобу.",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($delete_json);
    }

}