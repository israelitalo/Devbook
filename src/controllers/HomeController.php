<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class HomeController extends Controller {

    private $loggedUser;

    //Construtor do HomeContoller verifica se o usuário está logado para acessar a home
    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/login');
        }
    }

    public function index() {
        /*//separando o nome do usuário logado.
        $name = $this->loggedUser->getName();
        //O explode torna a variável $name em um array.
        $name = explode(' ', $name);
        $count = count($name);
        $lastName = $name[$count-1];*/

        $page = intval(filter_input(INPUT_GET, 'page'));

        //Preenchendo o feed
        $feed = PostHandler::getHomeFeed($this->loggedUser->getId(), $page);

        $this->render('home', [
            'loggedUser' => $this->loggedUser,
            /*'name' => $name[0],
            'lastName' => $lastName,*/
            'feed' => $feed
        ]);
    }

}
