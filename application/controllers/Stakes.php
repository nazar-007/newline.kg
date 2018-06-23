<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stakes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('stakes_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'stakes' => $this->stakes_model->getStakesByCategoryIds($category_ids),
            'stake_categories' => $this->stakes_model->getStakeCategories(),
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
}