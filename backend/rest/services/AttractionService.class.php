<?php
require_once __DIR__ . '/../dao/AttractionDao.class.php';

class AttractionService{
    private $attractions_dao;
    public function __construct(){
        $this-> attractions_dao = new AttractionDao();
    }
    public function add_attraction($attraction){
        return $this-> attractions_dao -> add_attraction($attraction);
    }
    public function get_all(){
        return $this-> attractions_dao -> get_all();
    }
    public function get_attraction($id){
        return $this-> attractions_dao -> find_attraction_by_id($id);
    }
    public function delete_attraction($id){
        return $this-> attractions_dao -> delete_attraction($id);
    }
    public function edit_attraction($attraction){
        return $this-> attractions_dao -> edit_attraction($attraction);
    }
}