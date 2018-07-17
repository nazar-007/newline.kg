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
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publication_images', $data);
    }

    public function delete_publication_image() {
        $id = $this->input->post('id');
        $publication_image_file = $this->publications_model->getPublicationImageFileById($id);
        unlink("./uploads/images/publication_images/$publication_image_file");
        $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($id);
        $this->publications_model->deletePublicationImageById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

}