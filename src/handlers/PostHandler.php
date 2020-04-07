<?php
    namespace src\handlers;
    use \src\models\Post;
    use \src\models\User;
    use \src\models\UserRelation;

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

        public function _postListToObject($postList, $idUser){
            //Transformar o retorno em objetos dos models
            $posts = [];
            foreach($postList as $postItem){
                $newPost = new Post();
                $newPost->id = $postItem['id'];
                $newPost->type = $postItem['type'];
                $newPost->created_at = $postItem['created_at'];
                $newPost->body = $postItem['body'];
                $newPost->mine = false;

                if($postItem['id_user'] == $idUser){
                    $newPost->mine = true;
                }

                //preencher as informações adicionais no post
                $newUser = User::select()->where('id', $postItem['id_user'])->one();
                $newPost->user = new User();
                $newPost->user->setId($newUser['id']);
                $newPost->user->setName($newUser['name']);
                $newPost->user->setAvatar($newUser['avatar']);

                $newPost->likeCount = 0;
                $newPost->like = false;

                $newPost->comments = [];

                $posts[] = $newPost;
            }
            return $posts;
        }

        public static function getUserFeed($idUser, $page, $loggedUserId){
            $perPage = 2;
            //pegar posts das pessoas seguidas ordenado por data
            $postList = Post::select()
                ->where('id_user', $idUser)
                ->orderBy('created_at', 'desc')
                ->page($page, $perPage)
                ->get();

            $countAllPages = Post::select()
                ->where('id_user', $idUser)
                ->count();

            $pageCount = ceil($countAllPages / $perPage);

            $posts = self::_postListToObject($postList, $loggedUserId);

            //retornar o resultado.
            return [
                'posts' => $posts,
                'pageCount' => $pageCount,
                'currentPage' => $page
            ];
        }

        public static function getHomeFeed($idUser, $page){
            $perPage = 2;
            //lista de usuários que o perfil logado segue
            $usersFollowing = UserRelation::select()
                ->where('user_from', $idUser)
            ->get();
            $userLIst = [];
            foreach($usersFollowing as $userItem){
                $userLIst[] = $userItem['user_to'];
            }
            //Adicionando o id do usuário logado, porque ele também vê as próprias publicações em seu feed.
            $userLIst[] = $idUser;

            //pegar posts das pessoas seguidas ordenado por data
            $postList = Post::select()
                ->where('id_user', 'in', $userLIst)
                ->orderBy('created_at', 'desc')
                ->page($page, $perPage)
            ->get();

            $countAllPages = Post::select()
                ->where('id_user', 'in', $userLIst)
            ->count();

            $pageCount = ceil($countAllPages / $perPage);

            //Transformar o retorno em objetos dos models

            $posts = self::_postListToObject($postList, $idUser);

            //retornar o resultado.
            return [
                'posts' => $posts,
                'pageCount' => $pageCount,
                'currentPage' => $page
            ];

        }

        public static function getPhotosFrom($idUser){
            $photosData = Post::select()
                ->where('id_user', $idUser)
                ->where('type', 'photo')
            ->get();

            $photos = [];

            foreach($photosData as $photo){
                $newPost = new Post();
                $newPost->id = $photo['id'];
                $newPost->created_at = $photo['created_at'];
                $newPost->body = $photo['body'];
                $newPost->type = $photo['type'];

                $photos[] = $newPost;
            }
            return $photos;
        }

    }
