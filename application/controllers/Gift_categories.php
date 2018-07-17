<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gifts_model');
    }

    public function Index() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $gifts = $this->gifts_model->getGiftsByCategoryIds($category_ids);
        $html = '';
        if (count($category_ids) == 0) {
            $html .= "<h3 class='centered'>Все подарки</h3>";
        } else {
            $html .= "<h3 class='centered'>Результаты по выбранным категориям</h3>";
        }
        foreach ($gifts as $gift) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 one_gift'>
            <span onclick='insertGiftSentPress(this)' data-toggle='modal' data-target='#insertGiftSent' data-id='$gift->id' data-gift_name='$gift->gift_name'>
                <div class='gift_image'>
                    <img src='" . base_url() . "uploads/images/gift_images/$gift->gift_file'>
                </div>
                <div class='gift_name'>
                    $gift->gift_name
                </div>";
            if ($gift->gift_price != 0) {
                $html .= "<div class='badge gift_price'>$gift->gift_price сом</div>";
            } else {
                $html .= "<div class='badge gift_price' style='background-color: orange'>Бесплатно!</div>";
            }
            $html .= "</span>
        </div>";
        }
        $get_json = array(
            'gifts_by_categories' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_json);
    }

    public function insert_gift_category() {
        $category_name = $this->input->post('category_name');

        $data_gift_categories = array(
            'category_name' => $category_name,
        );
        $this->gifts_model->insertGiftCategory($data_gift_categories);
    }

    public function delete_gift_category() {
        $id = $this->input->post('id');
        $this->gifts_model->deleteGiftCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_gift_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_gift_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->updateGiftCategoryById($id, $data_gift_categories);
    }

}