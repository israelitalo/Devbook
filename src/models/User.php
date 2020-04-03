<?php
    namespace src\models;
    use \core\Model;

    class User extends Model{

        private $id;
        private $email;
        private $name;
        private $birthdate;
        private $avatar;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getBirthdate()
        {
            return $this->birthdate;
        }

        public function setBirthdate($birthdate)
        {
            $this->birthdate = $birthdate;
        }

        public function getAvatar()
        {
            return $this->avatar;
        }

        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

    }
?>
