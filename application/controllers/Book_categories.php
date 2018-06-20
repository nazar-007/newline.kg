<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEvents($category_ids),
            'event_categories' => $this->events_model->getEventCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('events', $data);
    }


}