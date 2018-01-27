<?php
    include_once 'dal.php';
    include_once '../models/administrator.php';

    /*
      This class converts MySQL data into the project models (classes)
    */

    class BLL {

        public static function getAllAdmins () {
          $whereSql = "";
          return self::getAdminWhere($whereSql);
        }

        public static function getAdminsByRole ($role) {
          $whereSql = " WHERE `role`=".$role;
          return self::getAdminWhere($whereSql);
        }

        public static function getAdmin ($email, $password) {
          $whereSql = " WHERE `email`='".$email."' AND `password`='".$password."'";
          return self::getAdminWhere($whereSql);
        }

        private static function getAdminWhere ($whereSql) {
          $sql = 'SELECT * FROM `administrators`'.$whereSql;

          $adminsArray = [];
          foreach (DAL::getInstance($GLOBALS['dbDetails'])->fetch($sql) as $admin) {
            $adminsArray[] = new Administrator($admin);
          }

          return $adminsArray;
        }
    }

    // // test get admin
    // BLL::getAdmin('rakkafa.prog@gmail.com', 'e19d5cd5af0378da05f63f891c7467af');


 ?>
