<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_shares extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
    }

    public function Index() {
        $publication_id = 2;
        $data = array(
            'publication_shares' => $this->publications_model->getPublicationSharesByPublicationId($publication_id),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_shares', $data);
    }

    public function insert_publication_share() {
        $share_date = date("d.m.Y");
        $share_time = date("H:i:s");
        $share_user_id = $this->input->post('share_user_id');
        $publication_id = $this->input->post('publication_id');

        $data_publication_shares = array(
            'share_date' => $share_date,
            'share_time' => $share_time,
            'share_user_id' => $share_user_id,
            'publication_id' => $publication_id
        );
        $this->publications_model->insertPublicationShare($data_publication_shares);
    }

    public function delete_publication_share() {
        $id = $this->input->post('id');
        $this->publications_model->deletePublicationShareById($id);
        $this->publications_model->deletePublicationShareEmotionsByPublicationShareId($id);
        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }
}