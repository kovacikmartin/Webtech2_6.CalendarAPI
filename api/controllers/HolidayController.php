<?php

require_once("Database.php");

class HolidayController{

    private PDO $conn;

    public function __construct(){
        $this->conn = (new Database())->getConnection();
    }


    public function insertHoliday($holiday, $country, $day){

        try{

            $sql = "INSERT INTO t_holiday(holiday, country, day)
                                VALUES(?, ?, ?)";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$holiday, $country, $day]);
        }
        catch(PDOException $e){
            
            if($e->getCode() === '23000'){
    
                // unique constraint violated, holiday already in the table
            }
            else{
                echo "<div class='alert alert-danger' role='alert'>
                            Sorry, there was an error while inserting holiday ". $holiday .". " . $e->getMessage()."
                        </div>";
            }
        }
    }

    public function getHolidays($country){

        if(strlen($country) !== 2 || is_numeric($country)){
            
            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid country"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if(!in_array(strtoupper($country), $this->getHolidayCountries())){

            http_response_code(404);

            $array = [];
            $array["error"] = ["code" => 404, "message" => "Country not found"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        try{

            $sql = "SELECT holiday, day FROM t_holiday WHERE country = ?";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$country]);

            $holidays = [];

            $holidays["data"] = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($holidays, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error while retrieving holidays for country ". $country . ". " . $e->getMessage()."
                </div>";
        }
    }

    private function getHolidayCountries(){

        try{

            $sql = "SELECT DISTINCT(UPPER(country)) AS country FROM t_holiday";

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