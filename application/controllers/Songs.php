<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('songs_model');
        $this->load->model('users_model');
        $this->load->model('admins_model');
    }

    public function Index() {
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        $category_ids = array();
        if (isset($_POST['offset'])) {
            $offset = $this->input->post('offset');
        } else {
            $offset = 0;
        }

        $songs = $this->songs_model->getSongsByCategoryIds($category_ids, $offset);
        $html = '';
        foreach ($songs as $song) {
            $song_id = $song->id;
            $total_song_emotions = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_emotions');
            $total_song_fans = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_fans');
            $html .= "<div class='one-song col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                <div class='row'>
                    <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png' class='play-buttons'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png' class='play-buttons'>
                        </span>
                    </div>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8'>
                        <div class='actions'>
                            <span class='emotions_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                                <span class='badge' onclick='getSongEmotions(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongEmotions'>$total_song_emotions</span>
                            </span>
                            <span class='fans_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                                <span class='badge' onclick='getSongFans(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongFans'>$total_song_fans</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='song-name'>
                    <a href='" . base_url() . "one_song/$song_id'>
                        $song->song_singer - $song->song_name
                    </a>
                </div>
                <audio class='player' src='" . base_url() . "uploads/song_files/$song->song_file' controls controlsList='nodownload'></audio>
            </div>";
        }

        if (isset($_POST['offset'])) {
            $data = array(
                'songs' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            echo json_encode($data);
        } else {

            $friend_ids = array();
            $friends = $this->users_model->getFriendsByUserId($session_user_id);
            foreach ($friends as $friend) {
                $friend_ids[] = $friend->friend_id;
            }

            $data = array(
                'songs' => $html,
                'friend_ids' => $friend_ids,
                'total_songs' => $this->users_model->getTotalByFanUserIdAndFanTable($session_user_id, "song_fans"),
                'song_actions' => $this->songs_model->getSongActionsByFriendIds($friend_ids),
                'song_categories' => $this->songs_model->getSongCategories(),
                'my_fan_songs' => $this->songs_model->getSongFansByFanUserId($session_user_id),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('songs', $data);
        }
    }

    public function One_song($id) {
        $song_num_rows = $this->songs_model->getSongNumRowsById($id);
        $this->load->view('session_user');
        $session_user_id = $_SESSION['user_id'];
        if ($song_num_rows == 1) {
            $data_songs = array(
                'current_id' => $id,
                'one_song' => $this->songs_model->getOneSongById($id),
                'complaint_num_rows' => $this->songs_model->getSongComplaintNumRowsBySongIdAndComplainedUserId($id, $session_user_id),
                'emotion_num_rows' => $this->songs_model->getSongEmotionNumRowsBySongIdAndEmotionedUserId($id, $session_user_id),
                'fan_num_rows' => $this->songs_model->getSongFanNumRowsBySongIdAndFanUserId($id, $session_user_id),
                'total_emotions' => $this->songs_model->getTotalBySongIdAndSongTable($id, 'song_emotions'),
                'total_comments' => $this->songs_model->getTotalBySongIdAndSongTable($id, 'song_comments'),
                'total_fans' => $this->songs_model->getTotalBySongIdAndSongTable($id, 'song_fans'),
                'song_num_rows' => $song_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $data_songs = array(
                'current_id' => $id,
                'song_num_rows' => $song_num_rows,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        $this->load->view('one_song', $data_songs);
    }

    public function download_song() {
        $id = $this->input->get('id');
        $song_num_rows = $this->songs_model->getSongNumRowsById($id);

        if ($song_num_rows > 0) {
            $song_file = "uploads/song_files/" . $this->songs_model->getSongFileById($id);
            $this->load->helper("download");
            force_download($song_file, NULL);
        } else {
            echo "Не удалось скачать песню.:(";
        }
    }

    public function search_songs() {
        $search_by_name = $this->input->post('search_by_name');
        $html = '';
        if (iconv_strlen($search_by_name) > 0) {
            $html .= "<h3 class='centered'>Результаты по запросу $search_by_name</h3>";
            $songs = $this->songs_model->searchSongsBySongName($search_by_name);
            if (count($songs) == 0) {
                $html .= "<div class='red centered'>По Вашему запросу $search_by_name ничего не найдено! :(</div>";
            }
        } else {
            $html .= "<h3 class='centered'>Все песни</h3>";
            $songs = $this->songs_model->getSongsByCategoryIds(array(), 0);
        }
        foreach ($songs as $song) {
            $song_id = $song->id;
            $total_song_emotions = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_emotions');
            $total_song_fans = $this->songs_model->getTotalBySongIdAndSongTable($song_id, 'song_fans');
            $html .= "<div class='one-song col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                <div class='row'>
                    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                        <span class='play' onclick='playSong(this)'>
                            <img src='" . base_url() . "uploads/icons/play.png'>
                        </span>
                        <span class='pause' onclick='pauseSong(this)'>
                            <img src='" . base_url() . "uploads/icons/pause.png'>
                        </span>
                    </div>
                    <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                        <div class='actions'>
                            <span class='emotions_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/emotioned.png'>
                                <span class='badge' onclick='getSongEmotions(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongEmotions'>$total_song_emotions</span>
                            </span>
                            <span class='fans_field'>
                                <img onclick='putEmotionOrFan()' src='" . base_url() . "uploads/icons/fan.png'>
                                <span class='badge' onclick='getSongFans(this)' data-song_id='$song->id' data-toggle='modal' data-target='#getSongFans'>$total_song_fans</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='song-name'>
                    <a href='" . base_url() . "one_song/$song_id'>
                        $song->song_singer - $song->song_name
                    </a>
                </div>
                <audio class='player' src='" . base_url() . "uploads/song_files/$song->song_file' controls controlsList='nodownload'></audio>
            </div>";
        }
        $data = array(
            'search_songs' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function get_one_song_by_admin() {
        $id = $this->input->post('id');
        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] || $_SESSION['admin_table']) {
            $one_song = $this->songs_model->getOneSongById($id);
            $song_comments = $this->songs_model->getSongCommentsBySongId($id);
            $html = '';
            foreach ($one_song as $info_song) {
                $html .= "<h3 class='centered'>$info_song->song_singer - $info_song->song_name</h3>
                    <div>
                        <audio class='one-player' autoplay src='" . base_url() . "uploads/song_files/$info_song->song_file' controls controlsList='nodownload'></audio>
                    </div>
                    <div>
                        <strong class='song_th'>Текст песни: </strong>
                        <span class=song_td'>
                            <pre>
                                $info_song->song_lyrics
                            </pre>        
                        </span>
                    </div>
                <div>
                    <strong class='song_th'>Категория: </strong>
                    <span class='song_td'>$info_song->category_name</span>
                </div>";
            }

            $html .= "<h3 class='centered'>Комменты к песне</h3>";

            if (count($song_comments) == 0) {
                $html .= 'Комментов к данной песне пока нет';
            } else {
                foreach ($song_comments as $song_comment) {
                    $html .= "<div class='one_comment_$song_comment->id'>
                        <div class='commented_user'>
                            <img src='" . base_url() . "uploads/images/user_images/" . $song_comment->main_image . "' class='commented_avatar'>
                            $song_comment->nickname $song_comment->surname 
                            <span class='comment-date-time'>$song_comment->comment_date <br> $song_comment->comment_time</span>
                            <div onclick='deleteSongCommentByAdmin(this)' data-song_comment_id='$song_comment->id' data-comment_text='$song_comment->comment_text' class='right'>X</div>
                        </div>
                    <div class='comment_text'>
                       $song_comment->comment_text
                    </div>
                </div>";
                }
            }
            $get_json = array(
                'get_one_song' => $html,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $get_json = array(
                'get_error' => 'У вас нет прав на просмотр песни',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($get_json);
    }

    public function insert_song() {

        $this->load->library('upload');
        $config['upload_path'] = './uploads/song_files';
        $config['allowed_types'] = 'mp3';
        $song_file = str_replace('_mp3', '', str_replace('.', '_', $_FILES['song_file']['name']));
        $config['file_name'] = $song_file;
        $this->upload->initialize($config);

        $song_file_extension = pathinfo($song_file, PATHINFO_EXTENSION);

        if ($this->upload->do_upload('song_file')) {
            echo $song_file . '.' . $song_file_extension;
        }

        $data_songs = array(
            'song_name' => 'Beautiful Mess',
            'song_file' => $song_file . '.' . $song_file_extension,
            'song_singer' => 'Kristian Kostov',
            'song_lyrics' => 'So we stay in this mess, this beautiful mess tonight!',
            'category_id' => 3
        );

//      $this->songs_model->insertSong($data_songs);


//
//        $song_name = $this->input->post('song_name');
//        $song_file = $this->input->post('song_file');
//        $song_singer = $this->input->post('song_author');
//        $song_lyrics = $this->input->post('song_description');
//        $category_id = $this->input->post('category_id');
//
//        $data_songs = array(
//            'song_name' => $song_name,
//            'song_file' => $song_file,
//            'song_singer' => $song_singer,
//            'song_lyrics' => $song_lyrics,
//            'category_id' => $category_id
//        );

      //  $this->songs_model->insertSong($data_songs);
 //       $insert_song_id = $this->db->insert_id();

        // НАДО ДОДЕЛАТЬ ЭКШН
//
//        $song_action = 'Предложенную песню Назара "A million voices" одобрил админ.';
//        $data_song_actions = array(
//            'song_action' => $song_action,
//            'song_time_unix' => time(),
//            'action_user_id' => $user_id,
//            'song_id' => $insert_song_id
//        );
      //  $this->songs_model->insertSongAction($data_song_actions);

//        $notification_date = date('d.m.Y');
//        $notification_time = date('H:i:s');
//        if ($user_id != 0) {
//            $notification_text = 'Админ одобрил Вашу песню "A million voices". К Вашей валюте прибавился 1 сом, а к рейтингу - 5 баллов.';
//
//            $data_user_notifications = array(
//                'notification_type' => 'Одобрение Вашей песни',
//                'notification_text' => $notification_text,
//                'notification_date' => $notification_date,
//                'notification_time' => $notification_time,
//                'notification_viewed' => 'Не просмотрено',
//                'link_id' => $insert_song_id,
//                'link_table' => 'songs',
//                'user_id' => $user_id
//            );
//            $this->users_model->insertUserNotification($data_user_notifications);
//
//            $currency_before = $this->users_model->getCurrencyById($user_id);
//            $rating_before = $this->users_model->getRatingById($user_id);

//            $data_users = array(
//                'currency' => $currency_before + 1,
//                'rating' => $rating_before + 5
//            );
//            $this->users_model->updateUserById($user_id, $data_users);
//
//            $rating_after = $this->users_model->getRatingById($user_id);
//            $rank_after = $this->users_model->getRankById($user_id);
//            $this->users_model->updateRankById($user_id, $rating_after, $rank_after);
//
//            $suggestion_id = $this->input->post('suggestion_id');
//            $this->songs_model->deleteSongSuggestionById($suggestion_id);
//        }
    }

    public function delete_song() {
        $id = $this->input->post('id');
        $song_name = $this->input->post('song_name');
        $admin_id = $_SESSION['admin_id'];
        $admin_email = $_SESSION['admin_email'];
        $admin_table = $_SESSION['admin_table'];

        if (!$admin_id && !$admin_email && !$admin_table) {
            $delete_json = array(
                'song_error' => 'Не удалось удалить книгу',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $song_file = $this->songs_model->getSongFileById($id);
            unlink("./uploads/song_files/$song_file");

            $this->songs_model->deleteSongActionsBySongId($id);
            $this->songs_model->deleteSongCommentsBySongId($id);
            $this->songs_model->deleteSongComplaintsBySongId($id);
            $this->songs_model->deleteSongEmotionsBySongId($id);
            $this->songs_model->deleteSongFansBySongId($id);
            $this->songs_model->deleteSongById($id);

            $delete_json = array(
                'id' => $id,
                'csrf_hash' => $this->security->get_csrf_hash()
            );

            $data_admin_actions = array(
                'admin_action' => "$admin_email удалил песню $song_name под id $id",
                'admin_table' => $admin_table,
                'admin_date' => date('d.m.Y'),
                'admin_time' => date('H:i:s'),
                'action_admin_id' => $admin_id
            );
            $this->admins_model->insertAdminAction($data_admin_actions);
        }
        echo json_encode($delete_json);
    }

    public function update_song() {
        if ($_SESSION['admin_id'] && $_SESSION['admin_email'] && $_SESSION['admin_table']) {
            $id = $this->input->post('id');
            $song_name = $this->input->post('song_name');
            $song_singer = $this->input->post('song_singer');
            $song_lyrics = $this->input->post('song_lyrics');
            $data_songs = array(
                'song_name' => $song_name,
                'song_singer' => $song_singer,
                'song_lyrics' => $song_lyrics
            );
            $this->songs_model->updateSongById($id, $data_songs);

            $update_json = array(
                'id' => $id,
                'song_name' => $song_name,
                'song_singer' => $song_singer,
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $update_json = array(
                'update_error' => 'Не удалось сохранить изменения',
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($update_json);
    }
}