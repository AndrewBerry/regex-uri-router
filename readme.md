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

With some help from .htaccess, we can direct any request to a single entry point for our application and handle our request routing all from one place. If you would like to see more usage examples, see below.

## Installation

You can install this package via [Composer](https://getcomposer.org/). Run the following command:
```bash
composer require andrewberry/regex-uri-router
```

To use the router in your project, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):
```php
require_once("vendor/autoload.php");
```

If your project isn't using composer, you are able to download the files and manually add them to your project. The entire routing class is housed within `src/Regex_URI_Router.php`, just add this file to your project and load:
```php
include_once("path/to/inc/Regex_URI_Router.php");
```

## Usage: Callback Results and Continuation

It is important to understand that the `Regex_URI_Router::Route($request_uri)` function will attempt to match and execute routes in order. Only when a pattern matches the request URI does the router execute the associated callback function. The router will continue to execute callbacks that have a matching pattern until a callback returns a value of `true`.

With this in mind, it is easy to create a route that will act as a filter, pre-routing any requests. A good example of this would be a user authentication filter, see the below routes:

Order|Pattern|Description|Return Value
---|---|---|---
#1|`/^\/login\/$/`|Displays the login form.|`true` - no further routes are required.
#2|`/^\/admin\//`|This is our filter route, it will check if the user is able to view any route beginning with `/admin/`. If the user is not authenticated, the user is redirected to our `/login/` page and a value of `true` is returned.|`false` if user is authenticated which then allows further execution of the `/admin/X` routes below, or `true` and the user is redirected, starting a new request from the top.
#3|`/^\/admin\/edit\/$/`|This is an example of a restricted admin page, the logic behind checking the user's authentication status is kept neatly in route #2.|`true` - no further routes are required.
#4|`/^\/admin\/$/`|Our admin dashboard. Once again, authentication logic has already been executed in our #2 route - if this pattern is being check, it means our user has passed the earlier route.|`true` - no further routes are required.

> Please note: The order in which you add routes is the order in which they are checked and potentially executed.

An example implementation of the table above:
```php
include_once("vendor/autoload.php");

$router = new AndrewBerry\Regex_URI_Router();

$router->Add_Route("/^\/login\/$/", "Render_Login_Page"); // Render_Login_Page returns true;
$router->Add_Route("/^\/admin\//", "Authenticate_Admin"); // Authenticate_Admin redirects to /login/ if not authenticated and returns true or returns false if the user is authenticated (which allows execution of further /admin/* routes).
$router->Add_Route("/^\/admin\/edit\/$/", "Render_Admin_Edit_Page"); // This route is only checked IF the user is authenticated and false is returned from the "Authenticate_Admin" route. This route returns true.
$router->Add_Route("/^\/admin\/$/", "Render_Admin_Page"); // Again, this route is only checked IF the user is authenticated and false is returned from the "Authenticate_Admin" route. This route returns true.

$router->Route($_SERVER["REQUEST_URI"]);
```

## An Example .htaccess File
```apache
<IfModule mod_rewrite.c>
    RewriteEngine on
    
    # Add trailing slash
    RewriteCond %{REQUEST_URI} !(/$|\.) 
    RewriteRule (.*) %{REQUEST_URI}/ [R=301,L]
    
    # Rewrite any uri to index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php?route=$1 [NC,L,QSA]
</IfModule>
```
