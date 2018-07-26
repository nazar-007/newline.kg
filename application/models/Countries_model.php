<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Countries_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $sessions = array(
            'user_id' => $this->session->userdata('user_id'),
            'user_email' => $this->session->userdata('user_email'),
        );
        $this->session->set_userdata($sessions);
    }

    public function getCountries() {
        $query = $this->db->get('countries');
        return $query->result();
    }
}