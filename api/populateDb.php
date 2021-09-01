<?php

    require_once("controllers/PopulateDbController.php");

    $populateDbController = new PopulateDbController();

    $namedays = simplexml_load_file("meniny.xml");

    foreach($namedays->children() as $day){
        
        foreach($day->children() as $record){

            // insert holiday
            if(str_contains($record->getName(), "sviatky")){

                $country = substr($record->getName(), 0, 2);

                $populateDbController->insertHoliday($record, $country, $day->den);
            }

            // insert memorial
            else if(str_contains($record->getName(), "dni")){           
                
                $country = substr($record->getName(), 0, 2);

                $populateDbController->insertMemorial($record, $country, $day->den);
            }
            
            // insert names (for slovakia only SKd)
            else if($record->getName() !== "den" && $record->getName() !== "SK"){

                // multiple names on the same day
                if(str_contains($record, ",")){
                   
                    $names = explode(",", $record);
                    
                    foreach($names as $name){
                        
                        $country = ($record->getName() === "SKd") ? "SK" : $record->getName();

                        $name = trim($name);

                        if(strlen($name) > 1 && !empty($name)){
                            $populateDbController->insertName($name, $country, $day->den);
                        }
                    }
                }
                else{
                    
                    $country = ($record->getName() === "SKd") ? "SK" : $record->getName();

                    $populateDbController->insertName($record,  $country, $day->den);
                }    
            }
        }
    }
?>