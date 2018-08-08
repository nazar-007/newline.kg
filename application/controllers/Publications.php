<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publications extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('publications_model');
        $this->load->model('users_model');
        $this->load->model('albums_model');
    }
    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $friend_ids = array();
        $friends = $this->users_model->getFriendsByUserId($session_user_id);
        foreach ($friends as $friend) {
            $friend_ids[] = $friend->friend_id;
        }
        if (isset($_POST['offset'])) {
            $offset = $this->input->post('offset');
        } else {
            $offset = 0;
        }

        $html = '';

        if (count($friend_ids) == 0) {
            $html .= "<h3 class='centered'>Здесь появятся публикации Ваших друзей</h3>";
        } else {
            $publications = $this->publications_model->getPublicationsByFriendIds($friend_ids, $offset);
            foreach ($publications as $publication) {
                $published_user_id = $publication->published_user_id;
                $publication_id = $publication->id;
                $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
                $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $session_user_id);
                $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $session_user_id);
                $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $session_user_id);
                $total_publication_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
                $total_publication_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
                $total_publication_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
                $html .= "<div class='one_publication'>
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
                        $publication_image_id = $publication_image->id;
                        $image_emotion_num_rows = $this->publications_model->getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $session_user_id);
                        $total_publication_image_emotions = $this->publications_model->getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, 'publication_image_emotions');
                        if ($key == 0) {
                            $html .= "<div class='item active'>
                                                <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto;'>
                                                <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                                </div>
                                            </div>";
                        } else {
                            $html .= "<div class='item'>
                                                <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto'>
                                                <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                                </div>
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
                    $html .= "<img onclick='insertPublicationEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                } else {
                    $html .= "<img onclick='deletePublicationEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                }
                $html .= "<span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>$total_publication_emotions</span>
                        </span>
                        <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-commented_user_id='$session_user_id' data-publication_id='$publication_id'>
                            <span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>
                                <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_publication_comments</span>
                            </span>
                        </span>
                        <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($share_num_rows == 0) {
                    $html .= "<img onclick='insertPublicationShare(this)' src='" . base_url() . "uploads/icons/unshared.png'>";
                } else {
                    $html .= "<img onclick='deletePublicationShare(this)' src='" . base_url() . "uploads/icons/shared.png'>";
                }
                $html .= "<span class='badge' onclick='getPublicationShares(this)' data-toggle='modal' data-target='#getPublicationShares'>$total_publication_shares</span>
                        </span>
                        <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($complaint_num_rows == 0) {
                    $html .= "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' src='" . base_url() . "uploads/icons/complaint.png' class='right'>";
                }
                $html .= "</span></div></div>";
            }
        }
            $data = array(
                'publications' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            if (isset($_POST['offset']) && count($friend_ids) > 0) {
                echo json_encode($data);
            } else {
                $this->load->view('publications', $data);
            }
        }

    public function User_publications() {
        $user_id = $this->input->post('user_id');
        $session_user_id = $_SESSION['user_id'];
        $html = '';

        $publications = $this->publications_model->getPublicationsByPublishedUserId($user_id);

        if (count($publications) == 0) {
            $html .= "<h3 class='centered'>Пока нет публикаций</h3>";
        } else {
            foreach ($publications as $publication) {
                $published_user_id = $publication->published_user_id;
                $publication_id = $publication->id;
                $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
                $complaint_num_rows = $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($publication_id, $session_user_id);
                $emotion_num_rows = $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($publication_id, $session_user_id);
                $share_num_rows = $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($publication_id, $session_user_id);
                $total_publication_comments = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_comments');
                $total_publication_emotions = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_emotions');
                $total_publication_shares = $this->publications_model->getTotalByPublicationIdAndPublicationTable($publication_id, 'publication_shares');
                $html .= "<div class='one_publication one_pub_$publication_id'>
                    <div class='publication'>
                            <div class='bout_user'>";
                            if ($user_id == $session_user_id) {
                                $html .= "<button onclick='deletePressPublication(this)' class='btn-danger' data-toggle='modal' data-target='#deletePublication' data-id='$publication_id' data-published_user_id='$published_user_id'>
                                    X                                 
                                </button>";
                            }
                                $html .= "<span class='publication-date-time'>$publication->publication_date<br>$publication->publication_time</span>
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
                        $publication_image_id = $publication_image->id;
                        $image_emotion_num_rows = $this->publications_model->getPublicationImageEmotionNumRowsByPublicationImageIdAndEmotionedUserId($publication_image_id, $session_user_id);
                        $total_publication_image_emotions = $this->publications_model->getTotalByPublicationImageIdAndPublicationImageTable($publication_image_id, 'publication_image_emotions');
                        if ($key == 0) {
                            $html .= "<div class='item active'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto;'>
                                            <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                            </div>
                                        </div>";
                        } else {
                            $html .= "<div class='item'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto'>
                                            <div class='publication_image_emotion image_emotions_field_$publication_image_id' data-emotioned_user_id='$session_user_id' data-publication_image_id='$publication_image_id'>";
                            if ($image_emotion_num_rows == 0) {
                                $html .= "<img class='emotion_image' onclick='insertPublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                            } else {
                                $html .= "<img class='emotion_image' onclick='deletePublicationImageEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                            }
                            $html .= "<span class='badge' onclick='getPublicationImageEmotions(this)' data-toggle='modal' data-target='#getPublicationImageEmotions'>$total_publication_image_emotions</span>
                                            </div>
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
                    $html .= "<img onclick='insertPublicationEmotion(this)' src='" . base_url() . "uploads/icons/unemotioned.png'>";
                } else {
                    $html .= "<img onclick='deletePublicationEmotion(this)' src='" . base_url() . "uploads/icons/emotioned.png'>";
                }
                $html .= "<span class='badge' onclick='getPublicationEmotions(this)' data-toggle='modal' data-target='#getPublicationEmotions'>$total_publication_emotions</span>
                    </span>
                    <span class='comments_field_$publication_id' data-published_user_id='$published_user_id' data-commented_user_id='$session_user_id' data-publication_id='$publication_id'>
                        <span onclick='getPublicationComments(this)' data-toggle='modal' data-target='#getPublicationComments'>
                            <img src='" . base_url() . "uploads/icons/comment.png'><span class='badge'>$total_publication_comments</span>
                        </span>
                    </span>
                    <span class='shares_field_$publication_id' data-published_user_id='$published_user_id' data-shared_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($share_num_rows == 0) {
                    $html .= "<img onclick='insertPublicationShare(this)' src='" . base_url() . "uploads/icons/unshared.png'>";
                } else {
                    $html .= "<img onclick='deletePublicationShare(this)' src='" . base_url() . "uploads/icons/shared.png'>";
                }
                $html .= "<span class='badge' onclick='getPublicationShares(this)' data-toggle='modal' data-target='#getPublicationShares'>$total_publication_shares</span>
                    </span>
                    <span class='complaints_field_$publication_id' data-published_user_id='$published_user_id' data-complained_user_id='$session_user_id' data-publication_id='$publication_id'>";
                if ($complaint_num_rows != 1 && $user_id != $session_user_id) {
                    $html .= "<img onclick='insertPublicationComplaintPress(this)' data-toggle='modal' data-target='#insertPublicationComplaint' src='" . base_url() . "uploads/icons/complaint.png' class='right'>";
                }
                $html .= "</span></div></div>";
            }
        }
        $data = array(
            'user_publications' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function One_publication($id) {
        $publication_num_rows = $this->publications_model->getPublicationNumRowsById($id);
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        if ($publication_num_rows == 1) {
            $data_publications = array(
                'current_id' => $id,
                'one_publication' => $this->publications_model->getOnePublicationById($id),
                'publication_images' => $this->publications_model->getPublicationImagesByPublicationId($id),
                'complaint_num_rows' => $this->publications_model->getPublicationComplaintNumRowsByPublicationIdAndComplainedUserId($id, $session_user_id),
                'emotion_num_rows' => $this->publications_model->getPublicationEmotionNumRowsByPublicationIdAndEmotionedUserId($id, $session_user_id),
                'share_num_rows' => $this->publications_model->getPublicationShareNumRowsByPublicationIdAndSharedUserId($id, $session_user_id),
                'total_comments' => $this->publications_model->getTotalByPublicationIdAndPublicationTable($id, 'publication_comments'),
                'total_emotions' => $this->publications_model->getTotalByPublicationIdAndPublicationTable($id, 'publication_emotions'),
                'total_shares' => $this->publications_model->getTotalByPublicationIdAndPublicationTable($id, 'publication_shares'),
                'publication_num_rows' => $publication_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_publications = array(
                'current_id' => $id,
                'publication_num_rows' => $publication_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('one_publication', $data_publications);
    }

    public function get_one_publication_by_admin() {
        $id = $this->input->post('id');
        $html = '';
        $publications = $this->publications_model->getOnePublicationById($id);

            foreach ($publications as $publication) {
                $publication_id = $publication->id;
                $publication_images = $this->publications_model->getPublicationImagesByPublicationId($publication_id);
                $html .= "<div class='one_publication one_pub_$publication_id'>
                    <div class='publication'>
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
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto;'>
                                        </div>";
                        } else {
                            $html .= "<div class='item'>
                                            <img src='" . base_url() . "uploads/images/publication_images/$publication_image->publication_image_file' class='publication_images' style='width: initial; margin: 0 auto'>
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
                $html .= "</div></div>";
        }
        $data = array(
            'get_one_publication' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);

    }

    public function insert_publication() {
        $publication_name = $this->input->post('publication_name');
        $publication_description = $this->input->post('publication_description');
        $publication_date = date('d.m.Y');
        $publication_time = date('H:i:s');
        $published_user_id = $this->input->post('published_user_id');
        $session_user_id = $_SESSION['user_id'];
        $album_id = $this->albums_model->getPublicationAlbumIdByUserId($published_user_id);

        if ($published_user_id != $session_user_id) {
            $insert_json = array(
                'publication_error' => "Не удалось добавить публикацию. Возможно, Вы пытаетесь это через другого пользователя",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $publication_description = nl2br($publication_description);
            $data_publications = array(
                'publication_name' => $publication_name,
                'publication_description' => $publication_description,
                'publication_date' => $publication_date,
                'publication_time' => $publication_time,
                'published_user_id' => $published_user_id
            );

            $this->publications_model->insertPublication($data_publications);
            $insert_publication_id = $this->db->insert_id();

            $count_files = count($_FILES['publication_image']['name']);
            $files = $_FILES;
            $upload_images = array();

            if ($_FILES['publication_image']['name'][0] != '') {
                for ($i = 0; $i < $count_files; $i++) {
                        $_FILES['publication_image']['name'] = $files['publication_image']['name'][$i];
                        $_FILES['publication_image']['type'] = $files['publication_image']['type'][$i];
                        $_FILES['publication_image']['tmp_name'] = $files['publication_image']['tmp_name'][$i];
                        $_FILES['publication_image']['error'] = $files['publication_image']['error'][$i];
                        $_FILES['publication_image']['size'] = $files['publication_image']['size'][$i];

                        $config['upload_path'] = './uploads/images/publication_images';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['encrypt_name'] = true;
                        $this->load->library('upload', $config);

                        if ($this->upload->do_upload('publication_image')) {
                            $file_name = $this->upload->data()['file_name'];
                            $upload_images[] = $file_name;
                    }
                }
                $this->load->library('image_lib');

                foreach ($upload_images as $upload_image) {
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
            $insert_json = array(
                'publication_success' => "Публикация добавлена! Теперь она появится в ленте Ваших друзей!",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_publication_by_published_user_id() {
        $id = $this->input->post('id');
        $published_user_id = $this->input->post('published_user_id');
        $session_user_id = $_SESSION['user_id'];
        $publication_images = $this->publications_model->getPublicationImagesByPublicationId($id);
        if (count($publication_images) > 0) {
            foreach ($publication_images as $publication_image) {
                $publication_image_id = $publication_image->id;
                $publication_image_file = $this->publications_model->getPublicationImageFileById($publication_image_id);
                unlink("./uploads/images/publication_images/$publication_image_file");
                $this->publications_model->deletePublicationImageEmotionsByPublicationImageId($publication_image_id);
            }
        }
        $this->publications_model->deletePublicationCommentsByPublicationId($id);
        $this->publications_model->deletePublicationComplaintsByPublicationId($id);
        $this->publications_model->deletePublicationEmotionsByPublicationId($id);
        $this->publications_model->deletePublicationImagesByPublicationId($id);
        $this->publications_model->deletePublicationSharesByPublicationId($id);
        $this->publications_model->deletePublicationById($id);

        if ($published_user_id != $session_user_id) {
            $delete_json = array(
                'id' => $id,
                'publication_error' => "Вы не можете удалить чужую публикацию",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $delete_json = array(
                'id' => $id,
                'publication_success' => "Публикация удалена",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
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
            'link_id' => 0,
            'link_table' => 0,
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