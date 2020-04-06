<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use src\handlers\PostHandler;

class PostController extends Controller {

    private $loggedUser;

    //Construtor do HomeContoller verifica se o usuário está logado para acessar a home
    public function __construct(){
        $this->loggedUser = LoginHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/login');
        }
    }

    public function new() {
        $body = filter_input(INPUT_POST, 'body');

        if($body){
            PostHandler::addPost($this->loggedUser->getId(), 'text', $body);
        }

        $this->redirect('/');
    }

}
