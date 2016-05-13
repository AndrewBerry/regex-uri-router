<?php
    include_once("src/regex-uri-router.php");
    
    $router = new AndrewBerry\Regex_URI_Router();

    $router->Add_Route("/^\/add\/(\d+)\/(\d+)\/$/", function($args) {
        echo $args[0] + $args[1];
        return true;
    });

    $router->Add_Route("/^\/$/", function($args) {
        echo "Home!";
        return true;
    });

    $router->Add_Route("/^.*$/", function($args) {
        echo "404 - NOT FOUND!";
        return true;
    });

    $router->Route($_SERVER["REQUEST_URI"]);
