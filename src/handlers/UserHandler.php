<?php
namespace src\handlers;

use src\models\User;
use src\models\UserRelation;
use src\handlers\PostHandler;

class UserHandler{

    public static function checkLogin(){
        if(!empty($_SESSION['token'])){
            $token = addslashes($_SESSION['token']);
            $data = User::select()->where('token', $token)->one();
            if(count($data) > 0){
                $loggedUser = new User();
                $loggedUser->setId($data['id']);
                $loggedUser->setEmail($data['email']);
                $loggedUser->setName($data['name']);
                $loggedUser->setBirthdate($data['birthdate']);
                $loggedUser->setAvatar($data['avatar']);
                return $loggedUser;
            }
        }
        return false;
    }

    public static function verifyLogin($email, $password){
        $user = User::select()->where('email', $email)->one();
        if($user){
            if(password_verify($password, $user['password'])){
                $token = md5(time().rand(0,9999).time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                    ->execute();

                return $token;
            }
        }
        return false;
    }

    public static function emailExists($email){
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function idExists($id){
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    public static function getUser($id, $full = false){
        $data = User::select()
            ->where('id', $id)
        ->one();

        if($data){
            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setBirthdate($data['birthdate']);
            $user->city = $data['city'];
            $user->work = $data['work'];
            $user->setAvatar($data['avatar']);
            $user->cover = $data['cover'];

            if($full){
                $user->followers = [];
                $user->following = [];
                $user->photos = [];

                //followers
                $followers = UserRelation::select()->where('user_to', $id)->get();
                foreach($followers as $follower){
                    $userData = User::select()->where('id', $follower['user_from'])->one();

                    $newUser = new User();
                    $newUser->setId($userData['id']);
                    $newUser->setName($userData['name']);
                    $newUser->setAvatar($userData['avatar']);

                    $user->followers[] = $newUser;
                }

                //following
                $followings = UserRelation::select()->where('user_from', $id)->get();
                foreach($followings as $followingItem){
                    $userData = User::select()->where('id', $followingItem['user_to'])->one();

                    $newUser = new User();
                    $newUser->setId($userData['id']);
                    $newUser->setName($userData['name']);
                    $newUser->setAvatar($userData['avatar']);

                    $user->following[] = $newUser;
                }

                //photos
                $user->photos = PostHandler::getPhotosFrom($id);

            }

            return $user;
        }
        return false;
    }

    public static function addUser($name, $email, $password, $birthdate){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,9999).time());
        User::insert([
            'name' => $name,
            'email' => $email,
            'password' => $hash,
            'birthdate' => $birthdate,
            'token' => $token
        ])->execute();
        return $token;
    }

    public static function isFollowing($loggedUserId, $userId){
        $data = UserRelation::select()
            ->where('user_from', $loggedUserId)
            ->where('user_to', $userId)
        ->one();

        return ($data) ? true : false;
    }

    public static function follow($from, $to){
        UserRelation::insert([
            'user_from' => $from,
            'user_to' => $to
        ])->execute();
    }

    public static function unFollowing($from, $to){
        UserRelation::delete()
            ->where('user_from', $from)
            ->where('user_to', $to)
        ->execute();
    }

}
?>
