<?php

require_once("Database.php");

class MemorialController{

    private PDO $conn;

    public function __construct(){
        $this->conn = (new Database())->getConnection();
    }

    public function insertMemorial($memorial, $country, $day){

        try{

            $sql = "INSERT INTO t_memorial(memorial, country, day)
                                VALUES(?, ?, ?)";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$memorial, $country, $day]);
        }
        catch(PDOException $e){
            
            if($e->getCode() === '23000'){
    
                // unique constraint violated, memorial already in the table
            }
            else{
                echo "<div class='alert alert-danger' role='alert'>
                            Sorry, there was an error while inserting memorial " . $memorial . ". " . $e->getMessage() . "
                        </div>";
            }
        }
    }

    public function getMemorials($country){

        if(strlen($country) !== 2 || is_numeric($country)){
            
            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid country"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if(!in_array(strtoupper($country), $this->getMemorialCountries())){

            http_response_code(404);

            $array = [];
            $array["error"] = ["code" => 404, "message" => "Memorials not found"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        try{

            $sql = "SELECT memorial, day FROM t_memorial WHERE country = ?";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$country]);

            $memorials = [];

            $memorials["data"] = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($memorials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error while retrieving memorials for country ". $country . ". " . $e->getMessage()."
                </div>";
        }
    }

    private function getMemorialCountries(){

        try{

            $sql = "SELECT DISTINCT(UPPER(country)) AS country FROM t_memorial";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error while retrieving list of countries." . $e->getMessage()."
                </div>";
        }
    }
}
?>