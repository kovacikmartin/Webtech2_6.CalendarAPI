<?php

require_once("Database.php");

class PopulateDbController{

    private PDO $conn;

    public function __construct(){
        $this->conn = (new Database())->getConnection();
    }

    public function insertName($name, $country, $day){
        
        try{

            $sql = "INSERT INTO t_nameday(name, country, day)
                                VALUES(?, ?, ?)";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$name, $country, $day]);
        }
        catch(PDOException $e){
            
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error while inserting name ". $name ."for country " . $country . ". " . $e->getMessage()."
                    </div>";
        }
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
}
?>