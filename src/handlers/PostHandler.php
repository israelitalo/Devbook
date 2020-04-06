<?php
    namespace src\handlers;
    use \src\models\Post;

    date_default_timezone_set('America/Recife');
    class PostHandler {

        public static function addPost($idUser, $type, $body){
            $body = trim($body);

            if(!empty($idUser) && !empty($body)){

                Post::insert([
                    'id_user' => $idUser,
                    'type' => $type,
                    'created_at' => date('Y-m-d H:i:s'),
                    'body' => $body
                ])->execute();

            }
        }

    }
