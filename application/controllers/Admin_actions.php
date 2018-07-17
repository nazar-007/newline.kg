<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admins_model');
    }

    public function Index() {
        $data = array(
            'admin_actions' => $this->admins_model->getAdminActions(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('admin_notifications', $data);
    }

}