<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
    }
    public function Index() {
        $friend_ids = array();
        $offset = $this->input->post('offset');
        if (isset($offset)) {
            $offset = $this->input->post('offset');
        } else {
            $offset = 0;
        }
        if (!$_SESSION['user_id']) {
            redirect('/');
        }
        $publications = $this->publications_model->getPublicationsByFriendIds($friend_ids, $offset);
        $html = '';
        foreach ($publications as $publication) {
            $published_user_id = $publication->published_user_id;
            $session_user_id = $_SESSION['user_id'];
            $publication_id = $publication->id;
            $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
            $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $session_user_id);
            $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $session_user_id);
            $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $session_user_id);
            $total_publication_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
            $total_publication_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
            $total_publication_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
            $html .= "<div class='one_publication' style='background-color: linen; margin-bottom: 30px;'>
                        <div class='publication'>
                            <div class='bout_user'>
                                <a class='user_name' href='" . base_url() . "one_user/$publication->email'>" . $publication->nickname . " " . $publication->surname . "
                                    <img src='" . base_url() . "uploads/images/user_images/" . $publication->main_image . "' class='user_avatar'>
                                </a>
                                <span class='publication-date-time'>$publication->publication_date<br>$publication->publication_time</span>
                            </div>
                            <div class='user_publication'>
                                <h4 class='publication_name'>$publication->publication_name</h4>
                                <div class='publication_description'>" .
                                    $publication->publication_description . "
                                </div>
                            </div>";
                        if (count($publication_images) > 0) {
                            $html .= "<div id='carousel_$publication_id' class='carousel slide' data-interval='false' data-ride='carousel'>
                                <div class='carousel-inner'>";
                                foreach ($publication_images as $key => $publication_image) {
                                    if ($key == 0) {
                                        $html .= "<div class='item active'>
                                                <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                                            </div>";
                                    } else {
                                        $html .= "<div class='item'>
                                                <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                                            </div>";
                                    }
                                }
                                if (count($publication_images) > 1) {
                                    $html .= "<a class='left carousel-control' href='#carousel_$publication_id' data-slide='prev'>
                                  <span class='glyphicon glyphicon-chevron-left'></span>
                                  <span class='sr-only'>Previous</span>
                                </a>
                                <a class='right carousel-control' href='#carousel_$publication_id' data-slide='next'>
                                  <span class='glyphicon glyphicon-chevron-right'></span>
                                  <span class='sr-only'>Next</span>
                                </a>";
                                }
                            $html .= "</div>
                        </div>";
                        }
                    $html .= "</div>
                    <div class='actions'>
                        <span class='emotions_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>";
                        if ($emotion_num_rows == 0) {
                            $html .= "<img onclick='insertPublicationEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>";
                        } else {
                            $html .= "<img onclick='deletePublicationEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>";
                        }
                        $html .= "<span onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#viewPublicationEmotions'>$total_publication_emotions</span>
                        </span>
                        <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>
                            <img onclick='getOnePublication(this)' data-toggle='modal' data-target='#viewOnePublication' src='" . base_url() . "uploads/icons/comment.png'>$total_publication_comments
                        </span>
                        <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
                        if ($share_num_rows == 0) {
                            $html .= "<img onclick='insertPublicationShare(this)' src='" . base_url(). "uploads/icons/unshared.png'>";
                        } else {
                            $html .= "<img onclick='deletePublicationShare(this)' src='" . base_url(). "uploads/icons/shared.png'>";
                        }
                        $html .= "<span onclick='getPublicationShares(this)' data-toggle='modal' data-target='#viewPublicationShares'>$total_publication_shares</span>
                        </span>
                        <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
                        if ($complaint_num_rows == 0) {
                            $html .= "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' onclick='insertPublicationComplaint(this)' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
                        }
                        $html .= "</div></div>";
        }
        $data = array(
            'publications' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('publications', $data);
    }

    public function loadmore_publications() {
        $friend_ids = array();
        $offset = $this->input->post('offset');
        $publications = $this->publications_model->getPublicationsByFriendIds($friend_ids, $offset);
        $html = '';
        foreach ($publications as $publication) {
            $published_user_id = $publication->published_user_id;
            $session_user_id = $_SESSION['user_id'];
            $publication_id = $publication->id;
            $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
            $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $session_user_id);
            $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $session_user_id);
            $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $session_user_id);
            $total_publication_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
            $total_publication_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
            $total_publication_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
            $html .= "<div class='one_publication' style='background-color: linen; margin-bottom: 30px;'>
                        <div class='publication'>
                            <div class='bout_user'>
                                <a class='user_name' href='" . base_url() . "one_user/$publication->email'>" . $publication->nickname . " " . $publication->surname . "</a>
                                <img src='" . base_url() . "uploads/images/user_images/" . $publication->main_image . "' class='user_avatar'>
                                <span class='publication-date-time'>$publication->publication_date<br>$publication->publication_time</span>
                            </div>
                            <div class='user_publication'>
                                <h4 class='publication_name'>$publication->publication_name</h4>
                                <div class='publication_description'>" .
                $publication->publication_description . "
                                </div>
                            </div>";
            if (count($publication_images) > 0) {
                $html .= "<div id='carousel_$publication_id' class='carousel slide' data-interval='false' data-ride='carousel'>
                                <div class='carousel-inner'>";
                foreach ($publication_images as $key => $publication_image) {
                    if ($key == 0) {
                        $html .= "<div class='item active'>
                            <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                        </div>";
                    } else {
                        $html .= "<div class='item'>
                            <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                        </div>";
                    }
                }
                if (count($publication_images) > 1) {
                    $html .= "<a class='left carousel-control' href='#carousel_$publication_id' data-slide='prev'>
                                  <span class='glyphicon glyphicon-chevron-left'></span>
                                  <span class='sr-only'>Previous</span>
                                </a>
                                <a class='right carousel-control' href='#carousel_$publication_id' data-slide='next'>
                                  <span class='glyphicon glyphicon-chevron-right'></span>
                                  <span class='sr-only'>Next</span>
                                </a>";
                }
                $html .= "</div>
                        </div>";
            }
            $html .= "</div>
                    <div class='actions'>
                        <span class='emotions_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($emotion_num_rows == 0) {
                $html .= "<img onclick='insertPublicationEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>";
            } else {
                $html .= "<img onclick='deletePublicationEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>";
            }
            $html .= "<span onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#viewPublicationEmotions'>$total_publication_emotions</span>
                        </span>
                        <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>
                            <img onclick='getOnePublication(this)' data-toggle='modal' data-target='#viewOnePublication' src='" . base_url() . "uploads/icons/comment.png'>$total_publication_comments
                        </span>
                        <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($share_num_rows == 0) {
                $html .= "<img onclick='insertPublicationShare(this)' src='" . base_url(). "uploads/icons/unshared.png'>";
            } else {
                $html .= "<img onclick='deletePublicationShare(this)' src='" . base_url(). "uploads/icons/shared.png'>";
            }
            $html .= "<span onclick='getPublicationShares(this)' data-toggle='modal' data-target='#viewPublicationShares'>$total_publication_shares</span>
                        </span>
                        <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($complaint_num_rows == 0) {
                $html .= "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' onclick='insertPublicationComplaint(this)' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
            }
            $html .= "</div></div>";
        }
        $data = array(
            'loadmore' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function get_one_publication() {
        $id = $this->input->post("id");
        $publications = $this->publications_model->getOnePublicationById($id);
        $html = '';
        $csrf_hash = $this->security->get_csrf_hash();
        foreach ($publications as $publication) {
            $published_user_id = $publication->published_user_id;
            $session_user_id = $_SESSION['user_id'];
            $session_user_email = $_SESSION['user_email'];
            $publication_id = $publication->id;
            $publication_comments = $this->publications_model->getPublicationCommentsByPublicationId($publication_id);
            $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
            $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $session_user_id);
            $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $session_user_id);
            $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $session_user_id);
            $total_publication_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
            $total_publication_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
            $total_publication_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
            $html .= "<div class='one_publication' style='background-color: linen; margin-bottom: 30px;'>
                        <div class='publication'>
                            <div class='bout_user'>
                                <a class='user_name' href='" . base_url() . "one_user/$publication->email'>" . $publication->nickname . " " . $publication->surname . "</a>
                                <img src='" . base_url() . "uploads/images/user_images/" . $publication->main_image . "' class='user_avatar'>
                                <span class='publication-date-time'>$publication->publication_date</span>
                            </div>
                            <div class='user_publication'>
                                <h4 class='publication_name'>$publication->publication_name</h4>
                                <div class='publication_description'>" .
                                    $publication->publication_description . "
                                </div>
                            </div>";
            if (count($publication_images) > 0) {
                $html .= "<div id='one_carousel_$publication_id' class='carousel slide' data-interval='false' data-ride='carousel'>
                                <div class='carousel-inner'>";
                foreach ($publication_images as $key => $publication_image) {
                    if ($key == 0) {
                        $html .= "<div class='item active'>
                                    <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                                </div>";
                    } else {
                        $html .= "<div class='item'>
                                    <img src='/uploads/images/publication_images/$publication_image->publication_image_file' class='post_images' style='max-height: 50vh'>
                                </div>";
                    }
                }
                if (count($publication_images) > 1) {
                    $html .= "<a class='left carousel-control' href='#one_carousel_$publication_id' data-slide='prev'>
                                  <span class='glyphicon glyphicon-chevron-left'></span>
                                  <span class='sr-only'>Previous</span>
                                </a>
                                <a class='right carousel-control' href='#one_carousel_$publication_id' data-slide='next'>
                                  <span class='glyphicon glyphicon-chevron-right'></span>
                                  <span class='sr-only'>Next</span>
                                </a>";
                }
                $html .= "</div>
                        </div>";
            }
            $html .= "</div>
                    <div class='actions'>
                        <span class='emotions_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($emotion_num_rows == 0) {
                $html .= "<img onclick='insertPublicationEmotion(this)' src='" . base_url(). "uploads/icons/unemotioned.png'>$total_publication_emotions";
            } else {
                $html .= "<img onclick='deletePublicationEmotion(this)' src='" . base_url(). "uploads/icons/emotioned.png'>$total_publication_emotions";
            }
            $html .= "</span>
                        <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-emotioned_user_id='$session_user_id' data-publication_id='$publication_id'>
                            <img src='" . base_url() . "uploads/icons/comment.png'>$total_publication_comments
                        </span>
                        <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($share_num_rows == 0) {
                $html .= "<img onclick='insertPublicationShare(this)' src='" . base_url(). "uploads/icons/unshared.png'>$total_publication_shares";
            } else {
                $html .= "<img onclick='deletePublicationShare(this)' src='" . base_url(). "uploads/icons/shared.png'>$total_publication_shares";
            }
            $html .= "</span>
                        <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
            if ($complaint_num_rows == 0) {
                $html .= "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' onclick='insertPublicationComplaint(this)' src='" . base_url(). "uploads/icons/complaint.png' class='right'>";
            }
            $html .= "</div></div>
                    <form action='javascript:void(0)' onsubmit='insertPublicationComment(this)'>
                        <input type='hidden' class='csrf' name='csrf_test_name' value='$csrf_hash'>
                        <textarea id='comment_text' class='form-control comment-input' placeholder='Добавить коммент' name='comment_text'></textarea>
                        <input class='published_user_id' type='hidden' name='published_user_id' value='$published_user_id'>
                        <input class='commented_user_id' type='hidden' name='commented_user_id' value='$session_user_id'>
                        <input class='publication_id' type='hidden' name='publication_id' value='$publication_id'>
                        <button class='btn btn-success center-block' type='submit'>Комментировать</button>
                    </form>
                    <div class='comments_by_publication'>";
            if (count($publication_comments) > 0) {
                foreach ($publication_comments as $publication_comment) {
                    $html .= "<div class='one_comment_$publication_comment->id'>
                                <div class='commented_user'>
                                    <a href='" . base_url() . "one_user/$publication_comment->email'>
                                        <img src='" . base_url() . "uploads/images/user_images/" . $publication_comment->main_image . "' class='commented_avatar'>
                                        $publication_comment->nickname $publication_comment->surname 
                                    </a>
                                    <span class='comment-date-time'>$publication_comment->comment_date <br> $publication_comment->comment_time</span>";
                                    if ($publication_comment->email == $session_user_email) {
                                        $html .= "<div onclick='deletePublicationComment(this)' data-publication_comment_id='$publication_comment->id' data-publication_id='$publication_id' class='right'>X</div>";
                                    }
                                $html .= "</div>
                                <div class='comment_text'>
                                   $publication_comment->comment_text
                                </div>
                            </div>";
                }
            }
            $html .= "</div></div>";
        }
        $insert_json = array(
            'one_publication' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($insert_json);
    }

    public function insert_publication() {
        $publication_name = $this->input->post('book_name');
        $publication_description = $this->input->post('book_file');
        $publication_date = date('d.m.Y');
        $publication_time = date('d.m.Y');
        $album_id = $this->input->post('album_id');
        $published_user_id = $this->input->post('published_user_id');

        $data_publication = array(
            'publication_name' => $publication_name,
            'publication_description' => $publication_description,
            'publication_date' => $publication_date,
            'publication_time' => $publication_time,
            'published_user_id' => $published_user_id
        );

        $this->publications_model->insertPublication($data_publication);
        $insert_publication_id = $this->db->insert_id();

        $count_files = count($_FILES['publication_image']['name']);
        $files = $_FILES;
        $upload_images = array();

        for ($i = 0; $i < $count_files; $i++) {
            $_FILES['publication_image']['name'] = $files['publication_image']['name'][$i];
            $_FILES['publication_image']['type'] = $files['publication_image']['type'][$i];
            $_FILES['publication_image']['tmp_name'] = $files['publication_image']['tmp_name'][$i];
            $_FILES['publication_image']['error'] = $files['publication_image']['error'][$i];
            $_FILES['publication_image']['size'] = $files['publication_image']['size'][$i];

            $config['upload_path'] = './uploads/images/publication_images';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|ico|svg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('publication_image')) {
                $errors = array('error' => $this->upload->display_errors());
                echo 'не удалось загрузить файлы';
            } else {
                $file_name = $this->upload->data()['file_name'];
                $upload_images[] = $file_name;
            }
        }
        $this->load->library('image_lib');

        foreach ($upload_images as $upload_image) {
            $config['image_library'] = 'gd2';
            $config['source_image'] = "./uploads/images/publication_images/$upload_image";
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 100;
            $config['height'] = 50;
            $config['new_image'] = "./uploads/images/publication_images/thumb/$upload_image";
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $data_publication_images = array(
                'publication_image_file' => $upload_image,
                'publication_image_date' => $publication_date,
                'publication_image_time' => $publication_time,
                'album_id' => $album_id,
                'publication_id' => $insert_publication_id
            );
            $this->publications_model->insertPublicationImage($data_publication_images);
        }
    }

    public function delete_publication_by_user() {
        $id = $this->input->post('id');
        $publication_images = $this->publications_model->getPublicationImagesByPublicationId($id);
        foreach ($publication_images as $publication_image) {
            $publication_image_id = $publication_image->id;
            $publication_image_file = $this->publications_model->getPublicationImageFileById($publication_image_id);
            unlink("./uploads/images/publication_images/$publication_image_file");
            unlink("./uploads/images/publication_images/thumb/$publication_image_file");
            $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($publication_image_id);
        }
        $publication_shares = $this->publications_model->getPublicationSharesByPublicationId($id);
        foreach ($publication_shares as $publication_share) {
            $publication_share_id = $publication_share->id;
            $this->publications_model->deletePublicationShareEmotionsByPublicationShareId($publication_share_id);
        }

        $this->publications_model->deletePublicationCommentsByPublicationId($id);
        $this->publications_model->deletePublicationComplaintsByPublicationId($id);
        $this->publications_model->deletePublicationEmotionsByPublicationId($id);
        $this->publications_model->deletePublicationImagesByPublicationId($id);
        $this->publications_model->deletePublicationSharesByPublicationId($id);
        $this->publications_model->deletePublicationById($id);

        $delete_json = array(
            'id' => $id,
            'csrf_name' => $this->security->get_csrf_token_name (),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($delete_json);
    }

    public function delete_publication_by_admin_without_ban() {
        $user_id = $this->input->post('user_id');
        $this->delete_publication_by_user();
        $notification_text = 'Админ удалил Вашу публикацию "Тема 18+".';

        $data_user_notifications = array(
            'notification_type' => 'Удаление Вашей публикации',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function delete_publication_by_admin_with_ban() {

        $this->delete_publication_by_user();
        $user_id = $this->input->post('user_id');
        $notification_text = 'Админ забанил Ваш аккаунт из-за Вашей публикации "Тема 18+" и удалил её. Теперь Вы не можете добавлять публикации. Если Вы считаете, что бан произошёл несправедливо, сообщите об ошибке.';

        $data_users = array(
            'my_account_access' => "Закрыто"
        );
        $this->users_model->updateUserById($user_id, $data_users);

        $data_user_notifications = array(
            'notification_type' => 'Бан Вашего аккаунта',
            'notification_text' => $notification_text,
            'notification_date' => date('d.m.Y'),
            'notification_time' => date('H:i:s'),
            'notification_viewed' => 'Не просмотрено',
            'user_id' => $user_id
        );
        $this->users_model->insertUserNotification($data_user_notifications);
    }

    public function update_publication() {
        $id = $this->input->post('id');
        $publication_name = $this->input->post('book_name');
        $publication_description = $this->input->post('book_file');

        $data_publication = array(
            'publication_name' => $publication_name,
            'publication_description' => $publication_description
        );

        $this->publications_model->updatePublicationById($id, $data_publication);
    }

}