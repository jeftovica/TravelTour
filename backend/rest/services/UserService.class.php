<?php
require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService{
    private $user_dao;
    public function __construct(){
        $this-> user_dao = new UserDao();
    }
    public function add_user($user){
        return $this-> user_dao -> add_user($user);
    }
    public function login($credentials){
        $user = $this->user_dao->find_user_by_email($credentials["email"]);
        if($user && $user["password"]==$credentials["password"]){
            return ["name"=>$user["name"],
             "surname"=>$user["surname"],
             "email"=>$user["email"],
             "role"=>$user["is_admin"]==1? "admin":"user"
            ];
        }
        return null;
    }
    

}