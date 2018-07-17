<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stake_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $stakes = $this->stakes_model->getStakesByCategoryIds($category_ids);
        $html = '';
        if (count($category_ids) == 0) {
            $html .= "<h3 class='centered'>Все награды</h3>";
        } else {
            $html .= "<h3 class='centered'>Результаты по выбранным категориям</h3>";
        }
        foreach ($stakes as $stake) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 one_stake'>
                        <span onclick='insertStakeFanPress(this)' data-toggle='modal' data-target='#insertStakeFan' data-id='$stake->id' data-stake_name='$stake->stake_name'>
                            <div class='stake_image'>
                                <img src='" . base_url() . "uploads/images/stake_images/$stake->stake_file'>
                            </div>
                            <div class='stake_name'>
                                $stake->stake_name
                            </div>";
            if ($stake->stake_price != 0) {
                $html .= "<div class='badge stake_price'>$stake->stake_price сом</div>";
            } else {
                $html .= "<div class='badge stake_price' style='background-color: orange'>Бесплатно!</div>";
            }
            $html .= "</span>
            </div>";
        }
        $get_json = array(
            'stakes_by_categories' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_json, JSON_UNESCAPED_UNICODE);
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