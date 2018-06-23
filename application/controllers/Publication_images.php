<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_images extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $publication_id = 1;
        $data = array(
            'publication_images' => $this->albums_model->getPublicationImagesByPublicationId($publication_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_images', $data);
    }

}