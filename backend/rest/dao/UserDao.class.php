<?php
require_once __DIR__ . "/BaseDao.class.php";

class UserDao extends BaseDao{
    public function __construct(){
        parent::__construct('users');
    }

    public function add_user($user){
        $query = "INSERT INTO travel_tour.users (name, surname, email, phone_number, is_admin, password) VALUES 
        (:name, :surname, :email, :phone, 0, :password)";
        $statement= $this->connection->prepare($query);
        $statement->execute($user);
        $user['id']=$this->connection->lastInsertId();
        return $user;
    }
    public function find_user_by_email($email){
        $query = "SELECT * FROM travel_tour.users WHERE email=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user){
            return $user;
        }
        return null;
    }
}
