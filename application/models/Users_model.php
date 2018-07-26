<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
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
    public function getUsers() {
        $query = $this->db->get('users');
        return $query->result();
    }
    public function getBirthDateById($id) {
        $this->db->select('id, birth_date');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $birth_date = $user->birth_date;
        }
        return $birth_date;
    }
    public function getBirthYearById($id) {
        $this->db->select('id, birth_year');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $birth_year = $user->birth_year;
        }
        return $birth_year;
    }
    public function getCurrencyById($id) {
        $this->db->select('id, currency');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $currency = $user->currency;
        }
        return $currency;
    }
    public function getEducationSchoolsById($id) {
        $this->db->select('id, education_schools');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $education_schools = $user->education_schools;
        }
        return $education_schools;
    }
    public function getEducationUniversitiesById($id) {
        $this->db->select('id, education_universities');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $education_universities = $user->education_universities;
        }
        return $education_universities;
    }
    public function getEmailById($id) {
        $this->db->select('id, email');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $email = $user->email;
        }
        return $email;
    }
    public function getFamilyPositionById($id) {
        $this->db->select('id, family_position');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $family_position = $user->family_position;
        }
        return $family_position;
    }
    public function getFriendNumRowsByUserIdAndFriendId($user_id, $friend_id) {
        $this->db->select('user_id, friend_id');
        $this->db->where('user_id', $user_id);
        $this->db->where('friend_id', $friend_id);
        $query = $this->db->get('friends');
        return $query->num_rows();
    }
    public function getFriendsByUserId($user_id) {
        $this->db->select('friends.*, users.email, users.nickname, users.surname, users.main_image, users.last_visit');
        $this->db->from('friends');
        $this->db->join('users', 'friends.friend_id = users.id');
        $this->db->order_by('friend_date DESC');
        $this->db->where('friends.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getGuestsByUserId($user_id) {
        $this->db->select('guests.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('guests');
        $this->db->join('users', 'guests.guest_id = users.id');
        $this->db->order_by('guest_date DESC, guest_time DESC');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getGuestNumRowsByUserIdAndGuestId($user_id, $guest_id) {
        $this->db->select('user_id, guest_id');
        $this->db->where('user_id', $user_id);
        $this->db->where('guest_id', $guest_id);
        $query = $this->db->get('guests');
        return $query->num_rows();
    }
    public function getHomeLandById($id) {
        $this->db->select('id, home_land');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $home_land = $user->home_land;
        }
        return $home_land;
    }
    public function getMainImageById($id) {
        $this->db->select('id, main_image');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $main_image = $user->main_image;
        }
        return $main_image;
    }
    public function getNicknameAndSurnameById($id) {
        $this->db->select('id, nickname, surname');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $nickname = $user->nickname;
            $surname = $user->surname;
        }
        return $nickname . " " . $surname;
    }
    public function getNumRowsByEmail($email) {
        $this->db->select('id, email');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows();
    }
    public function getNumRowsByEmailAndPassword($email, $password) {
        $this->db->select('id, email, password');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        return $query->num_rows();
    }
    public function getOneUserByEmail($email) {
        $this->db->select('id, email, nickname, surname');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->result();
    }
    public function getOneUserById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->result();
    }
    public function getOnlineFriendsByUserId($user_id) {
        $this->db->select('friends.*, users.email, users.nickname, users.surname, users.main_image, users.last_visit');
        $this->db->from('friends');
        $this->db->join('users', 'friends.friend_id = users.id');
        $this->db->order_by('friend_date DESC');
        $this->db->where('friends.user_id', $user_id);
        $this->db->where('users.last_visit', 'Online');
        $query = $this->db->get();
        return $query->result();
    }
    public function getPasswordById($id) {
        $this->db->select('id, password');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $password = $user->password;
        }
        return $password;
    }
    public function getRankById($id) {
        $this->db->select('id, rank');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $rank = $user->rank;
        }
        return $rank;
    }
    public function getRatingById($id) {
        $this->db->select('id, rating');
        $this->db->where('id', $id);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $rating = $user->rating;
        }
        return $rating;
    }
    public function getUserBlacklistByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_blacklist');
        return $query->result();
    }
    public function getUserComplaintsByAdminId($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get('user_complaints');
        return $query->result();
    }
    public function getUsersByGuestId($guest_id) {
        $this->db->select('guests.*, users.email, users.nickname, users.surname, users.main_image');
        $this->db->from('guests');
        $this->db->join('users', 'guests.user_id = users.id');
        $this->db->order_by('guest_date DESC, guest_time DESC');
        $this->db->where('guests.guest_id', $guest_id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getUserIdByEmail($email) {
        $this->db->select('id, email');
        $this->db->where('email', $email);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $user_id = $user->id;
        }
        return $user_id;
    }
    public function getUserIdByEmailAndPassword($email, $password) {
        $this->db->select('id, email, password');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get("users");
        $users = $query->result();
        foreach ($users as $user) {
            $user_id = $user->id;
        }
        return $user_id;
    }
    public function getUserImageActionsByFriendIds($friend_ids) {
        foreach ($friend_ids as $key => $friend_id) {
            if ($key == 0) {
                $this->db->where('action_user_id', $friend_id);
            } else {
                $this->db->or_where('action_user_id', $friend_id);
            }
        }
        $query = $this->db->get('user_image_actions');
        return $query->result();
    }
    public function getUserImageActionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $query = $this->db->get('user_image_actions');
        return $query->result();
    }
    public function getUserImagesByAlbumId($album_id) {
        $this->db->where('album_id', $album_id);
        $query = $this->db->get('user_images');
        return $query->result();
    }
    public function getUserImageByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_images');
        return $query->result();
    }
    public function getUserImageFileById($id) {
        $this->db->select('id, user_image_file');
        $this->db->where('id', $id);
        $query = $this->db->get('user_images');
        $user_images = $query->result();
        foreach ($user_images as $user_image) {
            $user_image_file = $user_image->user_image_file;
        }
        return $user_image_file;
    }
    public function getUserInvitesByUserId($user_id) {
        $this->db->select('user_invites.*, users.email, users.nickname, users.surname, users.main_image, users.last_visit');
        $this->db->from('user_invites');
        $this->db->join('users', 'user_invites.invited_user_id = users.id');
        $this->db->order_by('invite_date DESC');
        $this->db->where('user_invites.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserInviteNumRowsByUserIdAndInvitedUserId($user_id, $invited_user_id) {
        $this->db->select('user_id, invited_user_id');
        $this->db->where('user_id', $user_id);
        $this->db->where('invited_user_id', $invited_user_id);
        $query = $this->db->get('user_invites');
        return $query->num_rows();
    }
    public function getUserNotificationsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id DESC');
        $query = $this->db->get('user_notifications');
        return $query->result();
    }
    public function getUserNumRowsByEmail($email) {
        $this->db->select('email');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows();
    }
    public function getTotalCommonFriendsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT COUNT(*) as total
            FROM friends
            WHERE friend_id IN 
            (SELECT friend_id 
            FROM friends 
            WHERE user_id 
            IN ($user1,$user2) 
            GROUP BY friend_id 
            HAVING COUNT(friend_id) = 2) 
            AND user_id = $user1"
        );
        $friends = $query->result();
        foreach ($friends as $friend) {
            $total = $friend->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total) == 6) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total) == 9) {
                return substr($total, 0, 3) . "M";
            } else if (strlen($total) == 10) {
                return substr($total, 0, 1) . "B";
            } else if(strlen($total) == 11) {
                return substr($total, 0, 2) . "B";
            } else if(strlen($total) == 12) {
                return substr($total, 0, 3) . "B";
            } else {
                return $total;
            }
        }
    }
    public function getCommonFriendsByTwoUsers($user1, $user2) {
        $query = $this->db->query(
            "SELECT friends.*, users.email, users.nickname, users.surname, users.main_image
                FROM friends
                INNER JOIN users ON(friends.friend_id = users.id)
                WHERE friend_id IN
                (SELECT friend_id
                FROM friends
                WHERE user_id IN ($user1,$user2)
                GROUP BY friend_id
                HAVING COUNT(friend_id) = 2)
                AND friends.user_id = $user1"
        );
        return $query->result();
    }
    public function getPossibleFriendsByUserId($user_id) {
        $query = $this->db->query("SELECT DISTINCT(user_id), users.email, users.nickname, users.surname, users.main_image, users.last_visit
            FROM friends
            INNER JOIN users
            ON friends.user_id = users.id
            WHERE friend_id
            IN
            (SELECT friend_id
            FROM friends
            WHERE user_id = $user_id)
            AND user_id != $user_id
            AND user_id NOT IN (SELECT friend_id FROM friends
            WHERE user_id = $user_id) ORDER BY rand() LIMIT 20 OFFSET 0");
        return $query->result();
    }
    public function getPossibleFriendsByFanUserIdAndTableName($fan_user_id, $table_name) {
        $table_fans = $table_name . "_fans";
        $table_id = $table_name . "_id";
        $this->db->order_by('rand()');
        $this->db->limit(2);
        $query = $this->db->query(
            "SELECT DISTINCT(fan_user_id), users.email, users.nickname, users.surname, users.main_image, users.last_visit
            FROM $table_fans
            INNER JOIN users
            ON $table_fans.fan_user_id = users.id
            WHERE $table_id
            IN
            (SELECT $table_id
            FROM $table_fans
            WHERE fan_user_id = $fan_user_id)
            AND fan_user_id != $fan_user_id
            AND fan_user_id NOT IN (SELECT friend_id FROM friends
            WHERE user_id = $fan_user_id) ORDER BY rand() LIMIT 20 OFFSET 0"
        );
        return $query->result();
    }
    public function getTotalByUserIdAndUserTable($user_id, $user_table) {
        $this->db->select('COUNT(*) as total');
        $this->db->group_by('user_id');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($user_table);
        $users = $query->result();
        foreach ($users as $user) {
            $total = $user->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total) == 6) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total) == 9) {
                return substr($total, 0, 3) . "M";
            } else if (strlen($total) == 10) {
                return substr($total, 0, 1) . "B";
            } else if(strlen($total) == 11) {
                return substr($total, 0, 2) . "B";
            } else if(strlen($total) == 12) {
                return substr($total, 0, 3) . "B";
            } else {
                return $total;
            }
        }
    }
    public function getTotalOnlineFriendsByUserId($user_id) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('friends');
        $this->db->join('users', 'friends.friend_id = users.id');
        $this->db->where('friends.user_id', $user_id);
        $this->db->where('users.last_visit', 'Online');
        $query = $this->db->get();
        $users = $query->result();
        foreach ($users as $user) {
            $total = $user->total;
            if (strlen($total) == 4) {
                return substr($total, 0, 1) . "K";
            } else if(strlen($total) == 5) {
                return substr($total, 0, 2) . "K";
            } else if(strlen($total) == 6) {
                return substr($total, 0, 3) . "K";
            } else if (strlen($total) == 7) {
                return substr($total, 0, 1) . "M";
            } else if(strlen($total) == 8) {
                return substr($total, 0, 2) . "M";
            } else if(strlen($total) == 9) {
                return substr($total, 0, 3) . "M";
            } else if (strlen($total) == 10) {
                return substr($total, 0, 1) . "B";
            } else if(strlen($total) == 11) {
                return substr($total, 0, 2) . "B";
            } else if(strlen($total) == 12) {
                return substr($total, 0, 3) . "B";
            } else {
                return $total;
            }
        }
    }
    public function searchUsers($search_value) {
        $this->db->select('id, email, nickname, surname, main_image, last_visit');
        $this->db->like("CONCAT(nickname, ' ', surname)", $search_value);
        $this->db->or_like('email', $search_value);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function insertUser($data) {
        $this->db->insert('users', $data);
    }
    public function insertUserBlacklist($data) {
        $this->db->insert('user_blacklist', $data);
    }
    public function insertFriend($data) {
        $this->db->insert('friends', $data);
    }
    public function insertGuest($data) {
        $this->db->insert('guests', $data);
    }
    public function insertUserComplaint($data) {
        $this->db->insert('user_complaints', $data);
    }
    public function insertUserImage($data) {
        $this->db->insert('user_images', $data);
    }
    public function insertUserImageAction($data) {
        $this->db->insert('user_image_actions', $data);
    }
    public function insertUserImageEmotion($data) {
        $this->db->insert('user_image_emotions', $data);
    }
    public function insertUserInvite($data) {
        $this->db->insert('user_invites', $data);
    }
    public function insertUserNotification($data) {
        $this->db->insert('user_notifications', $data);
    }
    public function insertUserPageEmotion($data) {
        $this->db->insert('user_page_emotions', $data);
    }

    public function deleteFriendByUserIdAndFriendId($user_id, $friend_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('friend_id', $friend_id);
        $this->db->delete('friends');
    }
    public function deleteFriendsByUserIdOrFriendId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('friend_id', $user_id);
        $this->db->delete('friends');
    }
    public function deleteGuestById($id) {
        $this->db->where('id', $id);
        $this->db->delete('guests');
    }
    public function deleteGuestsByUserIdOrGuestId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('friend_id', $user_id);
        $this->db->delete('guests');
    }
    public function deleteUserById($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }
    public function deleteUserBlacklistById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_blacklist');
    }
    public function deleteUserBlacklistByUserIdOrBlackUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('black_user_id', $user_id);
        $this->db->delete('user_blacklist');
    }
    public function deleteUserComplaintById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_complaints');
    }
    public function deleteUserComplaintsByComplainedUserId($complained_user_id) {
        $this->db->where('complained_user_id', $complained_user_id);
        $this->db->delete('user_complaints');
    }
    public function deleteUserComplaintsByUserIdOrComplainedUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('complained_user_id', $user_id);
        $this->db->delete('user_complaints');
    }
    public function deleteUserImageById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_images');
    }
    public function deleteUserImagesByAlbumId($album_id) {
        $this->db->where('album_id', $album_id);
        $this->db->delete('user_images');
    }
    public function deleteUserImagesByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_images');
    }
    public function deleteUserImageActionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_actions');
    }
    public function deleteUserImageActionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_actions');
    }
    public function deleteUserImageActionsByUserIdOrActionUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('action_user_id', $user_id);
        $this->db->delete('user_image_actions');
    }
    public function deleteUserImageEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_image_emotions');
    }
    public function deleteUserImageEmotionsByUserImageId($user_image_id) {
        $this->db->where('user_image_id', $user_image_id);
        $this->db->delete('user_image_emotions');
    }
    public function deleteUserImageEmotionsByUserIdOrEmotionedUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('emotioned_user_id', $user_id);
        $this->db->delete('user_image_emotions');
    }
    public function deleteUserInviteByUserIdAndInvitedUserId($user_id, $invited_user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('invited_user_id', $invited_user_id);
        $this->db->delete('user_invites');
    }
    public function deleteUserInvitesByUserIdOrInvitedUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('invited_user_id', $user_id);
        $this->db->delete('user_invites');
    }
    public function deleteUserNotificationById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_notifications');
    }
    public function deleteUserNotificationsByUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_notifications');
    }
    public function deleteUserPageEmotionById($id) {
        $this->db->where('id', $id);
        $this->db->delete('user_page_emotions');
    }
    public function deleteUserPageEmotionsByUserIdOrEmotionedUserId($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('emotioned_user_id', $user_id);
        $this->db->delete('user_page_emotions');
    }

    public function updateGuestByUserIdAndGuestId($user_id, $guest_id, $data) {
        $this->db->where('user_id', $user_id);
        $this->db->where('guest_id', $guest_id);
        $this->db->update('guests', $data);
    }
    public function updateRankById($id, $rating, $rank) {
        if ($rating < 0) {
            $data = array(
                'rank' => 'Лузер'
            );
        } else if ($rating >= 0 && $rating < 100) {
            $data = array(
                'rank' => 'Новичок'
            );
        } else if ($rating >= 100 && $rating < 200) {
            $data = array(
                'rank' => "Умник"
            );
        } else if ($rating >= 200 && $rating < 300) {
            $data = array(
                'rank' => "Гений"
            );
        } else if ($rating >= 300 && $rating < 400) {
            $data = array(
                'rank' => "Мудрец"
            );
        } else if ($rating >= 400) {
            $data = array(
                'rank' => "Высший разум"
            );
        } else {
            $data = array(
                'rank' => $rank
            );
        }
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }
    public function updateUserById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }
    public function updateUserComplaintById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_complaints', $data);
    }
    public function updateUserImageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_image_emotions', $data);
    }
    public function updateUserPageEmotionById($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('user_page_emotions', $data);
    }
}