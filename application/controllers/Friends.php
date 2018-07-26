<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('books_model');
        $this->load->model('events_model');
        $this->load->model('songs_model');
    }

    public function Index() {
        $user_id = $_SESSION['user_id'];
        $friends = $this->users_model->getFriendsByUserId($user_id);
        $html = '';

        if (count($friends) == 0) {
            $html .= "<h3 class='centered'>У Вас пока нет друзей.</h3>";
        } else {
            foreach ($friends as $friend) {
                $friend_id = $friend->friend_id;
                $email = $friend->email;
                $nickname = $friend->nickname;
                $surname = $friend->surname;
                $main_image = $friend->main_image;
                $last_visit = $friend->last_visit;
                $friend_date = $friend->friend_date;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                if ($last_visit == "Online") {
                    $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                }
                $html .= "</a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                       <div class='centered'>
                            Вы друзья с $friend_date
                       </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }

        $data = array(
            'friends' => $html,
            'total_all_friends' => $this->users_model->getTotalByUserIdAndUserTable($user_id, 'friends'),
            'total_online_friends' => $this->users_model->getTotalOnlineFriendsByUserId($user_id),
            'total_user_invites' => $this->users_model->getTotalByUserIdAndUserTable($user_id, 'user_invites'),
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        if (isset($_POST['load'])) {
            echo json_encode($data);
        } else {
            $this->load->view('friends', $data);
        }
    }

    public function online_friends() {
        $user_id = $_SESSION['user_id'];
        $friends = $this->users_model->getOnlineFriendsByUserId($user_id);
        $html = '';
        if (count($friends) == 0) {
            $html .= "<h3 class='centered'>Сейчас никто из Ваших друзей не сидит онлайн.</h3>";
        } else {
            foreach ($friends as $friend) {
                $friend_id = $friend->friend_id;
                $email = $friend->email;
                $nickname = $friend->nickname;
                $surname = $friend->surname;
                $main_image = $friend->main_image;
                $friend_date = $friend->friend_date;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname <img src='" . base_url() . "uploads/icons/lamp.png'>
                            </a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                       <div class='centered'>
                            Вы друзья с $friend_date
                       </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }
        $data = array(
            'online_friends' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function user_invites() {
        $user_id = $_SESSION['user_id'];
        $user_invites = $this->users_model->getUserInvitesByUserId($user_id);
        $html = '';

        if (count($user_invites) == 0) {
            $html .= "<h3 class='centered'>Вам пока никто не предлагал дружбу.</h3>";
        } else {
            foreach ($user_invites as $user_invite) {
                $invited_user_id = $user_invite->invited_user_id;
                $email = $user_invite->email;
                $nickname = $user_invite->nickname;
                $surname = $user_invite->surname;
                $main_image = $user_invite->main_image;
                $invite_date = $user_invite->invite_date;
                $invite_time = $user_invite->invite_time;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $invited_user_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $invited_user_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $invited_user_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $invited_user_id);
                $html .= "<div class='row one-friend invite-$invited_user_id'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname                                
                            </a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$invited_user_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                       <div class='centered'>
                          <div>
                          Добавить в друзья?
                          </div>
                          <div data-user_id='$user_id' data-invited_user_id='$invited_user_id'>
                              <button onclick='deleteUserInvite(this)'><img src='" . base_url() . "uploads/icons/cancel.png'></button>
                              <button onclick='insertFriend(this)'><img src='" . base_url() . "uploads/icons/checked.png'></button>
                          </div>
                       </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                                                      <div>
                          Предложение отправлено<br> $invite_date<br>в $invite_time 
                          </div>
                        </div></div>
                   </div>";
            }
        }
        $data = array(
            'user_invites' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function possible_friends() {
        $user_id = $_SESSION['user_id'];
        $friend_friends = $this->users_model->getPossibleFriendsByUserId($user_id);
        $book_friends = $this->users_model->getPossibleFriendsByFanUserIdAndTableName($user_id, 'book');
        $event_friends = $this->users_model->getPossibleFriendsByFanUserIdAndTableName($user_id, 'event');
        $song_friends = $this->users_model->getPossibleFriendsByFanUserIdAndTableName($user_id, 'song');
        $html = '';

        if (count($friend_friends) == 0) {
            $html .= "<h4 class='centered'>Нет возможных друзей по друзьям.</h4>";
        } else {
            $html .= "<h3 class='centered possible_friends'>По друзьям: </h3>";
            foreach ($friend_friends as $friend_friend) {
                $friend_id = $friend_friend->user_id;
                $email = $friend_friend->email;
                $nickname = $friend_friend->nickname;
                $surname = $friend_friend->surname;
                $main_image = $friend_friend->main_image;
                $last_visit = $friend_friend->last_visit;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                if ($last_visit == "Online") {
                    $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                }
                $html .= "</a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }

        if (count($book_friends) == 0) {
            $html .= "<h4 class='centered'>Нет возможных друзей по книгам.</h4>";
        } else
            $html .= "<h3 class='centered possible_friends'>По книгам: </h3>";
        {
            foreach ($book_friends as $book_friend) {
                $friend_id = $book_friend->fan_user_id;
                $email = $book_friend->email;
                $nickname = $book_friend->nickname;
                $surname = $book_friend->surname;
                $main_image = $book_friend->main_image;
                $last_visit = $book_friend->last_visit;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                if ($last_visit == "Online") {
                    $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                }
                $html .= "</a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }

        if (count($event_friends) == 0) {
            $html .= "<h4 class='centered'>Нет возможных друзей по событиям.</h4>";
        } else {
            $html .= "<h3 class='centered possible_friends'>По событиям: </h3>";
            foreach ($event_friends as $event_friend) {
                $friend_id = $event_friend->fan_user_id;
                $email = $event_friend->email;
                $nickname = $event_friend->nickname;
                $surname = $event_friend->surname;
                $main_image = $event_friend->main_image;
                $last_visit = $event_friend->last_visit;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                if ($last_visit == "Online") {
                    $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                }
                $html .= "</a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }

        if (count($song_friends) == 0) {
            $html .= "<h4 class='centered'>Нет возможных друзей по песням.</h4>";
        } else {
            $html .= "<h3 class='centered'>По песням: </h3>";
            foreach ($song_friends as $song_friend) {
                $friend_id = $song_friend->fan_user_id;
                $email = $song_friend->email;
                $nickname = $song_friend->nickname;
                $surname = $song_friend->surname;
                $main_image = $song_friend->main_image;
                $last_visit = $song_friend->last_visit;
                $total_common_friends = $this->users_model->getTotalCommonFriendsByTwoUsers($user_id, $friend_id);
                $total_common_books = $this->books_model->getTotalCommonBooksByTwoUsers($user_id, $friend_id);
                $total_common_events = $this->events_model->getTotalCommonEventsByTwoUsers($user_id, $friend_id);
                $total_common_songs = $this->songs_model->getTotalCommonSongsByTwoUsers($user_id, $friend_id);
                $html .= "<div class='row one-friend'>
                <div class='col-xs-12'>
                    <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 about-user'>
                       <div class='centered'>
                            <a class='link-friend' href='" . base_url() . "one_user/$email'>
                                $nickname $surname";
                if ($last_visit == "Online") {
                    $html .= "<img src='" . base_url() . "uploads/icons/lamp.png'>";
                }
                $html .= "</a>
                       </div>
                    <div class='dropdown common-mark centered'>
                        <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>Общее между вами
                            <span class='caret'></span></button>
                        <ul class='dropdown-menu dropdown-menu-right' data-user_id='$user_id' data-friend_id='$friend_id'>";
                if ($total_common_friends == 0 && $total_common_books == 0 && $total_common_events == 0 && $total_common_songs == 0) {
                    $html .= "<li>
                                    <span>Нет ничего общего</span>
                                </li>";
                } else {
                    if ($total_common_songs > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonSongs(this)' data-toggle='modal' data-target='#getCommonSongs' class='right view-common'>общих песен: $total_common_songs</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_books > 0) {
                        $html .= "<li>
                                    <span>
                                          <span onclick='getCommonBooks(this)' data-toggle='modal' data-target='#getCommonBooks' class='right view-common'>общих книг: $total_common_books</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_friends > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonFriends(this)' data-toggle='modal' data-target='#getCommonFriends' class='right view-common'>общих друзей: $total_common_friends</span>
                                    </span>
                                </li>";
                    }
                    if ($total_common_events > 0) {
                        $html .= "<li>
                                    <span>
                                      <span onclick='getCommonEvents(this)' data-toggle='modal' data-target='#getCommonEvents' class='right view-common'>общих событий: $total_common_events</span>
                                    </span>
                                </li>";
                    }
                }
                $html .= "</ul>
                            </div>
                        </div>
                        <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
                            <img class='img-thumbnail' src='" . base_url() . "uploads/images/user_images/$main_image'>
                        </div></div>
                   </div>";
            }
        }

        $data = array(
            'possible_friends' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($data);
    }

    public function common_friends() {
        $this->load->view('session_user');
        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');
        $common_friends = $this->users_model->getCommonFriendsByTwoUsers($user_id, $friend_id);
        $html = '';
        foreach ($common_friends as $common_friend) {
            $html .= "<div class='col-xs-6 col-sm-4 col-lg-3 emotion_user'>
                        <a href='" . base_url() . "one_user/$common_friend->email'>
                            <div class='emotion_user_image'>
                                <img src='" . base_url() . "uploads/images/user_images/$common_friend->main_image' class='action_avatar'>
                            </div>
                            <div class='emotion_user_name'>
                                $common_friend->nickname $common_friend->surname
                            </div>
                        </a>
                    </div>";
        }
        $get_common_json = array(
            'common_friends' => $html,
            'csrf_hash' => $this->security->get_csrf_hash()
        );
        echo json_encode($get_common_json);
    }

    public function insert_friend() {
        $friend_date = date('d.m.Y');
        $friend_time = date('H:i:s');

        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');

        $user_name = $this->users_model->getNicknameAndSurnameById($user_id);
        $friend_name = $this->users_model->getNicknameAndSurnameById($friend_id);

        $user_num_rows = $this->users_model->getFriendNumRowsByUserIdAndFriendId($user_id, $friend_id);
        $friend_num_rows = $this->users_model->getFriendNumRowsByUserIdAndFriendId($friend_id, $user_id);

        if ($user_num_rows == 0 && $friend_num_rows == 0) {
            $data_friends_1 = array(
                'friend_date' => $friend_date,
                'friend_time' => $friend_time,
                'user_id' => $user_id,
                'friend_id' => $friend_id
            );
            $this->users_model->insertFriend($data_friends_1);

            $data_friends_2 = array(
                'friend_date' => $friend_date,
                'friend_time' => $friend_time,
                'user_id' => $friend_id,
                'friend_id' => $user_id
            );
            $this->users_model->insertFriend($data_friends_2);
            $this->users_model->deleteUserInviteByUserIdAndInvitedUserId($user_id, $friend_id);

            $notification_text = "$user_name принял Ваш запрос в друзья";
            $data_user_notifications = array(
                'notification_type' => 'Принятие дружбы',
                'notification_text' => $notification_text,
                'notification_date' => $friend_date,
                'notification_time' => $friend_time,
                'notification_viewed' => 'Не просмотрено',
                'link_id' => 1,
                'link_table' => 'friends',
                'user_id' => $user_id
            );
            $this->users_model->insertUserNotification($data_user_notifications);
            $insert_json = array(
                'friend_success' => "Теперь Вы друзья с $friend_name",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        } else {
            $insert_json = array(
                'friend_error' => "Не удалось подружиться с $friend_name",
                'csrf_hash' => $this->security->get_csrf_hash()
            );
        }
        echo json_encode($insert_json);
    }

    public function delete_friend() {
        $user_id = $this->input->post('user_id');
        $friend_id = $this->input->post('friend_id');
        $this->users_model->deleteFriends($user_id, $friend_id);
        $this->users_model->deleteFriends($friend_id, $user_id);
    }
}