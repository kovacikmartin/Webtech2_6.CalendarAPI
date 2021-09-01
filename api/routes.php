<?php
    require_once("controllers/HolidayController.php");
    require_once("controllers/MemorialController.php");
    require_once("controllers/NamedayController.php");

    $holidayController = new HolidayController();
    $memorialController = new MemorialController();
    $namedayController = new NamedayController();

    use Pecee\SimpleRouter\SimpleRouter as Router;
    use Pecee\Http\Request;
    use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

    Router::get("Foundation_and_Earth/api", function() {
        return "Hello world";
    });

    // all names on {day}
    Router::get("Foundation_and_Earth/api/namedays/{day}", "NamedayController@getNameday");

    // nameday for {name} and {country}
    Router::get("Foundation_and_Earth/api/nameday/{name}/{country}", "NamedayController@getDayByNameCountry")->where(["name" => "[A-Za-z0-9%]+"]);

    // holidays of {country}
    Router::get("Foundation_and_Earth/api/holidays/{country}", "HolidayController@getHolidays");

    // memorials of {country}
    Router::get("Foundation_and_Earth/api/memorials/{country}", "MemorialController@getMemorials");

    // all names on {day}
    Router::post("Foundation_and_Earth/api/nameday", function(){
        
        $input = input()->all();
        
        $name = $input["name"];
        $day = $input["day"];

        $namedayController = new NamedayController();

        return $namedayController->insertName($name, $day);
    });

    Router::get("/Foundation_and_Earth/api/not-found", function(){

        http_response_code(404);

        $array = [];
        $array["error"] = ["code" => 404, "message" => "Not found"]; 
        
        return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    });

    Router::error(function(Request $request, \Exception $exception) {

        
        if($exception instanceof NotFoundHttpException && $exception->getCode() === 404) {
            
            response()->redirect("/Foundation_and_Earth/api/not-found");
        }
        
    });
?>