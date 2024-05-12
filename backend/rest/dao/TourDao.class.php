<?php
require_once __DIR__ . "/BaseDao.class.php";

class TourDao extends BaseDao{
    public function __construct(){
        parent::__construct('tours');
    }
    public function add_tour($tour){
        $query = "INSERT INTO travel_tour.tours (name, description, start_date, end_date, price,image_url) VALUES 
        (:name, :description,:startDate,:endDate,:price,:image)";
        $statement= $this->connection->prepare($query);
        $statement->bindParam(':name', $tour['name']);
        $statement->bindParam(':description', $tour['description']);
        $statement->bindParam(':startDate', $tour['startDate']);
        $statement->bindParam(':endDate', $tour['endDate']);
        $statement->bindParam(':price', $tour['price']);
        $statement->bindParam(':image', $tour['image']);
        $statement->execute();
        $tour['id']=$this->connection->lastInsertId();
        foreach($tour['attractions'] as $attraction_id){
            $query = "INSERT INTO travel_tour.tours_attractions (tour_id, attraction_id) VALUES 
            (?,?)";
            $statement= $this->connection->prepare($query);
            $statement->execute([$tour['id'],strval($attraction_id)]);
        }
        return $tour;
    }
    public function get_all(){
        $query = "SELECT id,name,description,image_url,price,start_date,end_date FROM travel_tour.tours";
        $statement= $this->connection->prepare($query);
        $statement->execute();
        $tours = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($tours){
            return $tours;
        }
        return null;
    } 
    public function find_tour_by_id($id){
        $query = "SELECT * FROM travel_tour.tours WHERE id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $tour = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$tour){
            return null;
        }
        $query = "SELECT attractions.id,attractions.name,attractions.description,attractions.image_url
        FROM travel_tour.tours_attractions INNER JOIN travel_tour.attractions
        ON travel_tour.tours_attractions.attraction_id=travel_tour.attractions.id
        WHERE tour_id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $tour['attractions'] = $statement->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT users.id,users.name,users.surname,users.phone_number
        FROM travel_tour.reservations INNER JOIN travel_tour.users
        ON travel_tour.reservations.id_user=travel_tour.users.id
        WHERE id_tour=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $tour['reservations'] = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $tour;
        
    }
    public function add_reservation($tour_id,$user_id){
        $query = "INSERT INTO travel_tour.reservations (id_tour, id_user) VALUES 
        (?,?)";
        $statement= $this->connection->prepare($query);
        $statement->execute([$tour_id,$user_id]);
    }
    public function delete_tour($id){
        $query = "DELETE FROM travel_tour.tours_attractions WHERE tour_id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $query = "DELETE FROM travel_tour.reservations WHERE id_tour=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $query = "DELETE FROM travel_tour.tours WHERE id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        return null;
    }
    public function edit_tour($tour){
        $query = "UPDATE travel_tour.tours SET name=:name, description=:description, start_date=:startDate, end_date=:endDate, price=:price
        WHERE id=:id";
        if (isset($tour["image"])){
            $query = "UPDATE travel_tour.tours SET name=:name, description=:description, start_date=:startDate, end_date=:endDate,image_url=:image, price=:price
        WHERE id=:id";
        }
        $statement= $this->connection->prepare($query);
        $statement->bindParam(':name', $tour['name']);
        $statement->bindParam(':description', $tour['description']);
        $statement->bindParam(':startDate', $tour['startDate']);
        $statement->bindParam(':endDate', $tour['endDate']);
        $statement->bindParam(':price', $tour['price']);
        $statement->bindParam(':id', $tour['id']);
        if (isset($tour["image"])){
            $statement->bindParam(':image', $tour['image']);
        }
        $statement->execute();
        $query = "DELETE FROM travel_tour.tours_attractions WHERE tour_id=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$tour['id']]);
        foreach($tour['attractions'] as $attraction_id){
            $query = "INSERT INTO travel_tour.tours_attractions (tour_id, attraction_id) VALUES 
            (?,?)";
            $statement= $this->connection->prepare($query);
            $statement->execute([$tour['id'],strval($attraction_id)]);
        }
        return $tour;
    }
    public function get_popular_tours(){
        $query = "SELECT id,name,description,image_url,price,start_date,end_date FROM travel_tour.tours LIMIT 3";
        $statement= $this->connection->prepare($query);
        $statement->execute();
        $tours = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($tours){
            return $tours;
        }
        return null;
    } 
    public function find_tour_by_user_id($id){
        $query = "SELECT tours.id,tours.name,tours.description,tours.image_url,tours.price,tours.start_date,tours.end_date FROM travel_tour.tours
        INNER JOIN travel_tour.reservations ON travel_tour.reservations.id_tour=travel_tour.tours.id 
        WHERE id_user=?";
        $statement= $this->connection->prepare($query);
        $statement->execute([$id]);
        $tours = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($tours){
            return $tours;
        }
        return null;
    } 
}
