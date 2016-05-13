# andrewberry/regex-uri-router
[![Build Status](https://travis-ci.org/AndrewBerry/regex-uri-router.svg?branch=master)](https://travis-ci.org/AndrewBerry/regex-uri-router)

This package is a simple and flexible router that maps request URIs to user defined functions. andrewberry/regex-uri-router is built with flexability and simplicity in mind - Take a look at a quick eample:

```php
$router = new AndrewBerry\Regex_URI_Router();

$router->Add_Route("/^\/hello\/(\w+)\/$/", function($args) {
    echo "Hello, {$args[0]}";
    return true;
});

$router->Route($_SERVER["REQUEST_URI"]);
// A request to /hello/andrew/ will output
// Hello, andrew
```

## Installation

You can install this package via [Composer](https://getcomposer.org/). Run the following command:
```bash
composer require andrewberry/regex-uri-router
```

To use the router in your project, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):
```php
require_once("vendor/autoload.php");
```