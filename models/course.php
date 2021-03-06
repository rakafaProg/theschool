<?php

    class Course {
        protected $name;
        protected $description;
        protected $stdCount;
        private $id;
        protected $image;
        private $studentId;

        public function __construct($params) {
          $this->setName($params['name']);
          $this->id = $params['id'];
          $this->setImage($params['image']);
          $this->description = $params['description'];
          $this->stdCount = isset($params['student_cnt']) ? $params['student_cnt'] : 0;
          $this->studentId = isset($params['student-id']) ? $params['student-id'] : NULL;
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

        public function getDescription () {
            return $this->description;
        }

        public function setDescription ($description) {
            $this->description = $description;
        }

        public function getStudentsCount () {
            return $this->stdCount;
        }

        public function setStudentsCount ($stdCount) {
            $this->stdCount = $stdCount;
        }

        function getImage() {
            return $this->image;
        }

        function setImage($image) {
            $this->image = $image;
        }

        function getImageURL () {
            return '../images-uploading/courses/' . $this->image;
        }

        function getStudentId () {
          return $this->studentId;
        }
    }


 ?>
