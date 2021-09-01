<?php

    //require_once("api/populateDb.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Namedays API</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <div class="row mx-auto docu">
        <a href="https://documenter.getpostman.com/view/15439062/TzJshJoa" target="_blank" class="btn btn-primary col-12" title="API documentation">API Documentation</a>
    </div>

    <div id="response" class="row mx-auto">
    
    </div>

    <br><br>

    <div class="forms">

        <form class="form-inline row mx-auto">
            <div class="form-group col-auto">
                <input type="text" class="form-control" id="inputNamedayDay" placeholder="Day">
                
            </div>

            <button type="button" class="btn btn-primary col-auto" onclick="getNamedays();">Get namedays</button>
            
            <span id="wrongDayFormat"></span>
        </form>

        <form class="row mx-auto">
            <div class="form-group col-12">
                <label for="inputNamedayName">Name</label>
                <input type="text" class="form-control" id="inputNamedayName">
            </div>

            <div class="form-group col-12">
                <label for="selectNamedayCountry">Country</label>
                <select class="form-control" id="selectNamedayCountry">
                    <option>SK</option>
                    <option>CZ</option>
                    <option>PL</option>
                    <option>HU</option>
                    <option>AT</option>
                </select>
            </div>

            <span id="nameCountryError"></span>

            <button type="button" class="btn btn-primary col-12" onclick="getDayOfName();">Get nameday date</button>
        </form>

        <form class="form-inline row mx-auto">
            <span class="col-6">SK Memorials</span>
            <button type="button" class="btn btn-primary col-6" onclick="getMemorials();">Get Memorials</button>
        </form>

        <form class="form-inline row mx-auto">
            <div class="form-group col-6">
                <label for="selectHolidaysCountry">Holidays</label>
                <select class="form-control" id="selectHolidaysCountry">
                    <option>SK</option>
                    <option>CZ</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary col-6" onclick="getHolidays();">Get holidays</button>
        </form>

        <form class="row mx-auto" id="addName">
            <div class="form-group col-12">
                <label for="inputInsertNamedayDay">Day</label>
                <input type="text" class="form-control" id="inputInsertNamedayDay" name="day">
                <span id="wrongDayFormatInsert"></span>
            </div>

            <div class="form-group col-12">
                <label for="inputInsertNamedayName">Name</label>
                <input type="text" class="form-control" id="inputInsertNamedayName" name="name">
                <span id="wrongNameInsert"></span>
            </div>

            <button type="button" class="btn btn-primary col-12" onclick="insertName();">Insert name</button>
        </form>
    </div>

    <footer>
        <span>Webtech 2 - Homework 6 - </span>
        <span>Martin Kováčik</span>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>