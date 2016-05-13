# AndrewBerry/regex-uri-router

This package is a simple and flexible router that maps request URIs to user defined functions.

## A Quick Example
````
$router = new AndrewBerry\Regex_URI_Router();

$router->Add_Route("/^\/hello\/(\w+)\/$/", function($args) {
    echo "Hello, {$args[0]}";
    return true;
});

$router->Add_Route("/^\/$/", function() {
    echo "index";
    return true;
});

$router->Add_Route("/^.*$/", function() {
    echo "404";
    return true;
});

$router->Route($_SERVER["REQUEST_URI"]);
````
