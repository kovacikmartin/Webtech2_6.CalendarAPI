<?php

require_once("Database.php");

class NamedayController{

    private PDO $conn;

    public function __construct(){
        $this->conn = (new Database())->getConnection();
    }

    public function insertName($name, $day){

        if(is_numeric($name) || $name === "" || $name === null){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid name"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if(!is_numeric($day) || strlen($day) !== 4){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid day"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        //                                    months               days
        $validDayFormat = preg_match('/^(0?[1-9]|1[0-2])(0?[1-9]|[1-2][0-9]|30|31)$/', $day);

        if($validDayFormat === 0){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid day format"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        try{

            $sql = "INSERT INTO t_nameday(name, country, day)
                                VALUES(?, ?, ?)";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$name, "SK", $day]);

            http_response_code(200);

            $array = [];
            $array["success"] = ["code" => 200, "message" => "Name inserted"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error while inserting name ". $name . ". " . $e->getMessage()."
                    </div>";
        }
    }

    public function getNameday($day){

        if(!is_numeric($day) || strlen($day) !== 4){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid day"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        //                                    months               days
        $validDayFormat = preg_match('/^(0?[1-9]|1[0-2])(0?[1-9]|[1-2][0-9]|30|31)$/', $day);

        if($validDayFormat === 0){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid day format"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        try{

            $sql = "SELECT name, country FROM t_nameday WHERE day = ?";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$day]);

            $names = [];
            $names["data"] = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($names, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error while retrieving nameday for day ". $day . ". " . $e->getMessage()."
                </div>";
        }
    }

    public function getDayByNameCountry($name, $country){

        $name = urldecode($name);

        if(is_numeric($name)){

            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid name"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if(strlen($country) !== 2 || is_numeric($country)){
            
            http_response_code(400);

            $array = [];
            $array["error"] = ["code" => 400, "message" => "Invalid country"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        if(!in_array(strtoupper($country), $this->getNamedayCountries())){
            
            http_response_code(404);

            $array = [];
            $array["error"] = ["code" => 404, "message" => "Country not found"]; 
            
            return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    
        try{

            $sql = "SELECT day FROM t_nameday WHERE name = ? AND country = ?";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$name, $country]);

            $day = [];
            $day["data"] = $stmnt->fetchAll(PDO::FETCH_ASSOC);

            if($day["data"] === false){
                http_response_code(404);

                $array = [];
                $array["error"] = ["code" => 404, "message" => "Name not found"]; 
                
                return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
           
            return json_encode($day, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error while retrieving day for name ". $name . "and country " . $country . ". " . $e->getMessage()."
                </div>";
        }
    }

    private function getNamedayCountries(){

        try{

            $sql = "SELECT DISTINCT(UPPER(country)) AS country FROM t_nameday";

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