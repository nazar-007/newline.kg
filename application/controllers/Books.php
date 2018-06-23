<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Books extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $category_ids = array();
        $data = array(
            'books' => $this->books_model->getBooksByCategoryIds($category_ids),
            'book_categories' => $this->books_model->getBookCategories(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('books', $data);
    }

    public function choose_book_categories() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $books = $this->books_model->getBooksByCategoryIds($category_ids);
        foreach ($books as $book) {
            echo "<tr>
            <td>$book->id</td>
            <td>
                <a href='" . base_url() . "models/" . $book->id . "'>" . $book->book_name . "</a>
            </td>
         </tr>";
        }
    }

}