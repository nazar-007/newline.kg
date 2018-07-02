<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stake_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'stakes' => $this->stakes_model->getStakesByCategoryIds($category_ids),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('stakes', $data);
    }

    public function choose_stake_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $stakes = $this->stakes_model->getStakesByCategoryIds($category_ids);
        foreach ($stakes as $stake) {
            echo "<tr>
            <td>$stake->id</td>
            <td>
                <a href='" . base_url() . "models/" . $stake->id . "'>" . $stake->stake_name . "</a>
            </td>
         </tr>";
        }
    }

    public function insert_stake_category() {
        $category_name = $this->input->post('category_name');

        $data_stake_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->insertStakeCategory($data_stake_categories);
    }

    public function delete_stake_category() {
        $id = $this->input->post('id');
        $this->stakes_model->deleteStakeCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_stake_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_stake_categories = array(
            'category_name' => $category_name,
        );
        $this->stakes_model->updateStakeCategoryById($id, $data_stake_categories);
    }
}