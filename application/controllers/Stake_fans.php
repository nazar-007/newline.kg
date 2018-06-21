<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stake_fans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooks($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('books', $data);
    }

    public function choose_event_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $books = $this->books_model->getBooks($category_ids);
        foreach ($books as $book) {
            echo "<tr>
            <td>$book->id</td>
            <td>
                <a href='" . base_url() . "models/" . $book->id . "'>" . $book->book_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_stake_fan() {
        $stake_date = date('d.m.Y');
        $stake_time = date('H:i:s');
        $user_id = $this->input->post('user_id');
        $stake_id = $this->input->post('stake_id');

        $data_stake_fans = array(
            'stake_date' => $stake_date,
            'stake_time' => $stake_time,
            'user_id' => $user_id,
            'stake_id' => $stake_id
        );
        $this->stakes_model->insertStakeFan($data_stake_fans);
    }

    public function delete_stake_fan() {
        $id = $this->input->post('id');
        $this->stakes_model->deleteStakeFan($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}