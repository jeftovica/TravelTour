<?php
require_once __DIR__ . '/../dao/TourDao.class.php';

class TourService{
    private $tours_dao;
    public function __construct(){
        $this-> tours_dao = new TourDao();
    }
    public function add_tour($tour){
        return $this-> tours_dao -> add_tour($tour);
    }
    public function get_all(){
        return $this-> tours_dao -> get_all();
    }
    public function get_tour($id){
        return $this-> tours_dao -> find_tour_by_id($id);
    }
    public function add_reservation($tour_id,$user_id){
        return $this-> tours_dao -> add_reservation($tour_id,$user_id);
    }
    public function cancel_reservation($tour_id,$user_id){
        return $this-> tours_dao -> cancel_reservation($tour_id,$user_id);
    }
    public function delete_tour($id){
        return $this-> tours_dao -> delete_tour($id);
    }
    public function edit_tour($tour){
        return $this-> tours_dao -> edit_tour($tour);
    }
    public function get_popular_tours(){
        return $this-> tours_dao -> get_popular_tours();
    }
    public function get_my_tours($id){
        return $this-> tours_dao -> find_tour_by_user_id($id);
    }
}