<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\models\User;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

    private $loggedUser;

    //Construtor do HomeContoller verifica se o usuário está logado para acessar a home
    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/login');
        }
    }

    public function index($atts = []) {
        $page = intval(filter_input(INPUT_GET, 'page'));
        //Verificando o usuário acessado
        if(!empty($atts['id'])){
            $id = $atts['id'];
        }else{
            $id = $this->loggedUser->getId();
        }
        //pegando informações do usuário
        $user = UserHandler::getUser($id, true);

        if(!$user){
            $this->redirect('/');
        }

        try {
            $dateFrom = new \DateTime($user->getBirthdate());
        } catch (\Exception $e) {
            echo 'Erro na data do usuário: '.$e;
        }
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        //pegando o feed do usuário.
        $feed = PostHandler::getUserFeed($user->getId(), $page, $this->loggedUser->getId());

        //verificar se o perfil logado segue o perfil pesquisado
        $isFollowing = false;
        if($user->getId() != $this->loggedUser->getId()){
            $isFollowing = UserHandler::isFollowing($this->loggedUser->getId(), $user->getId());


        }

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    public function follow($atts){
        $to = intval(addslashes($atts['id']));

        if(UserHandler::idExists($to)){
            if(UserHandler::isFollowing($this->loggedUser->getId(), $to)){
                //deixar de seguir
                UserHandler::unFollowing($this->loggedUser->getId(), $to);
            }else{
                //seguir
                UserHandler::follow($this->loggedUser->getId(), $to);
            }
        }

        $this->redirect('/perfil/'.$to);

    }

}
