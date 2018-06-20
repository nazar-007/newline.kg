<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Countries_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');

    }
    public function getCountries() {
        $query = $this->db->get('countries');
        return $query->result();
    }
}