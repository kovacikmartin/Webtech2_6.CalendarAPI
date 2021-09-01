<?php
    header("Content-Type: application/json; charset=UTF-8");

    require __DIR__ . "/vendor/autoload.php";   
    
    use Pecee\SimpleRouter\SimpleRouter;

    require_once("helpers.php");
    require_once("routes.php");

    SimpleRouter::start();
?>