<?php

    class Person {
        protected $name;
        protected $phone;
        protected $email;
        private $id;

        public function __construct($params) {
          $this->setName($params['name']);
          $this->setPhone($params['phone']);
          $this->setEmail($params['email']);
          $this->id = $params['id'];
        }

        public function getId() {
          return $this->id;
        }

        public function getName () {
            return $this->name;
        }

        public function setName ($name) {
            $this->name = $name;
        }

        public function getPhone () {
            return $this->phone;
        }

        public function setPhone ($phone) {
            $this->phone = $phone;
        }

        public function getEmail () {
            return $this->email;
        }

        public function setEmail ($email) {
            $this->email = $email;
        }
    }


 ?>