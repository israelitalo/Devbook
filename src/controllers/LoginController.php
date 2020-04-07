<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use src\models\User;

class LoginController extends Controller {

    public function signin(){
        $msg = '';
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
            $msg = $_SESSION['msg'];
        }
        $this->render('signin', ['msg' => $msg]);
        unset($_SESSION['msg']);
    }

    public function signinAction(){
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if($email && $password){
            $token = UserHandler::verifyLogin($email, $password);
            if($token){
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }else{
                $_SESSION['msg'] = "E-mail ou senha inválidos.";
                $this->redirect('/login');
            }
        }else{
            $this->redirect('/login');
        }
    }

    public function signup(){
        $msg = '';
        if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
            $msg = $_SESSION['msg'];
        }
        $this->render('signup', ['msg' => $msg]);
        unset($_SESSION['msg']);
    }

    public function signupAtcion(){
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if($name && $email && $password && $birthdate){

            $birthdate = explode('/', $birthdate);
            if(count($birthdate) != 3){
                $_SESSION['msg'] = 'Data de nascimento inválida.';
                $this->redirect('/cadastro');
            }

            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
            if(strtotime($birthdate) === false){
                $_SESSION['msg'] = 'Data de nascimento inválida.';
                $this->redirect('/cadastro');
            }

            if(UserHandler::emailExists($email) === false){
                $token = UserHandler::addUser($name, $email, $password, $birthdate);
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }else{
                $_SESSION['msg'] = "E-mail já cadastrado.";
                $this->redirect('/cadastro');
            }

        }else{
            $this->redirect('/cadastro');
        }
    }

    public function logout(){
        unset($_SESSION['token']);
        $this->redirect('/login');
    }

}
