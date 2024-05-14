<?php
require_once __DIR__ . "/BaseDao.class.php";

class AttractionDao extends BaseDao{
    public function __construct(){
        parent::__construct('attractions');
    }
    public function add_attraction($attraction){
        $query = "INSERT INTO travel_tour.attractions (name, description, image_url) VALUES 
        (:name, :description, :image)";
        $statement= $this->connection->prepare($query);
        $statement->execute($attraction);
        $attraction['id']=$this->connection->lastInsertId();
        return $attraction;
    }
    public function find_attraction_by_id($id){
        $query = "SELECT * FROM travel_tour.attractions WHERE id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $attraction = $statement->fetch(PDO::FETCH_ASSOC);
        if ($attraction){
            return $attraction;
        }
        return null;
    }

    public function get_all(){
        $query = "SELECT * FROM travel_tour.attractions";
        $statement= $this->connection->prepare($query);
        $statement->execute();
        $attractions = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($attractions){
            return $attractions;
        }
        return null;
    }   

    public function delete_attraction($id){
        $query = "DELETE FROM travel_tour.tours_attractions WHERE attraction_id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);

        $query = "DELETE FROM travel_tour.attractions WHERE id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        return null;
    }

    public function edit_attraction($attraction){
        $query = "UPDATE travel_tour.attractions SET name=:name, description=:description where id=:id";
        if (isset($attraction['image'])){
            $query = "UPDATE travel_tour.attractions SET name=:name, description=:description,image_url=:image where id=:id";  
        }
        $statement= $this->connection->prepare($query);
        $statement->execute($attraction);
        return $attraction;
    }

}
