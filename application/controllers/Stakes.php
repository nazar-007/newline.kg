<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stakes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $category_ids = array();
        $user_id = $_SESSION['user_id'];
        $data = array(
            'stakes' => $this->stakes_model->getStakesByCategoryIds($category_ids),
            'my_stakes' => $this->stakes_model->getStakeFansByFanUserId($user_id),
            'stake_categories' => $this->stakes_model->getStakeCategories(),
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

    public function insert_stake() {
        $stake_name = $this->input->post('stake_name');
        $stake_file = $this->input->post('stake_file');
        $category_id = $this->input->post('category_id');

        $data_stakes = array(
            'stake_name' => $stake_name,
            'stake_file' => $stake_file,
            'category_id' => $category_id
        );
        $this->stakes_model->insertStake($data_stakes);
    }

    public function delete_stake() {
        $id = $this->input->post('id');
        $this->stakes_model->deleteStakeById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_stake() {
        $id = $this->input->post('id');
        $stake_name = $this->input->post('stake_name');
        $stake_file = $this->input->post('stake_file');
        $category_id = $this->input->post('category_id');

        $data_gifts = array(
            'stake_name' => $stake_name,
            'stake_file' => $stake_file,
            'category_id' => $category_id
        );
        $this->stakes_model->updateStakeById($id, $data_gifts);
    }
}