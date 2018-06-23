<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('events_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'events' => $this->events_model->getEventsByCategoryIds($category_ids),
            'event_categories' => $this->events_model->getEventCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('events', $data);
    }

    public function choose_event_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $events = $this->events_model->getEventsByCategoryIds($category_ids);
        foreach ($events as $event) {
            echo "<tr>
            <td>$event->id</td>
            <td>
                <a href='" . base_url() . "models/" . $event->id . "'>" . $event->event_name . "</a>
            </td>
         </tr>";
        }
    }

}