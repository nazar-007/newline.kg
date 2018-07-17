<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('books_model');
    }

    public function Index() {
        $choose_category_ids = $this->input->post('category_ids');
        $category_ids = array();
        if (isset($choose_category_ids)) {
            $category_ids = $choose_category_ids;
        }
        $books = $this->books_model->getBooksByCategoryIds($category_ids, 0);
        $html = '';
        foreach ($books as $book) {
            $book_id = $book->id;
            $book_name = $book->book_name;
            $total_book_emotions = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_emotions');
            $total_book_fans = $this->books_model->getTotalByBookIdAndBookTable($book_id, 'book_fans');
            $html .= "<div class='col-xs-6 col-sm-6 col-md-4 col-lg-4 one_book'>
                    <a href='" . base_url() . "one_book/$book_id'>
                        <div class='book_cover'>
                            <img class='book_image' src='" . base_url() . "uploads/images/book_images/$book->book_image'>             
                        </div>
                        <div class='book_name'>$book_name</div>
                    </a>
                    <div class='actions'>
                        <span class='emotions_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                            <span class='badge' onclick='getBookEmotions(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookEmotions'>$total_book_emotions</span>
                        </span>
                        <span class='fans_field'>
                            <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                            <span class='badge' onclick='getBookFans(this)' data-book_id='$book->id' data-toggle='modal' data-target='#getBookFans'>$total_book_fans</span>
                        </span>
                    </div>
                </div>";
        }
        $data = array(
            'books_by_categories' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function insert_book_category() {
        $category_name = $this->input->post('category_name');

        $data_book_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->insertBookCategory($data_book_categories);
    }

    public function delete_book_category() {
        $id = $this->input->post('id');
        $this->books_model->deleteBookCategoryById($id);
        $delete_json = array(
            'id' => $id,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function update_book_category() {
        $id = $this->input->post('id');
        $category_name = $this->input->post('category_name');

        $data_book_categories = array(
            'category_name' => $category_name,
        );
        $this->books_model->updateBookCategoryById($id, $data_book_categories);
    }

}