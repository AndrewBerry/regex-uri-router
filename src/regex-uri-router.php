<?php
    /*
     * This file is part of the AndrewBerry\Regex_URI_Router package.
     *
     * (c) 2016 Andrew Berry <andrew.berry@outlook.com>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     *
        todo:
            travis
            example uses/readme.md
    */

    namespace AndrewBerry;

    /**
     * Regex URI Router
     * 
     * Maps request URIs to user specified functions.
     *
     * @author Andrew Berry <andrew.berry@outlook.com>
     */
    class Regex_URI_Router {
        /**
         * Active routes to be iterated across with Regex_URI_Router::Route()
         *
         * @private
         * @var array
         */
        private $routes;
        
        /**
         * Getter for the private var $routes
         * 
         * @return array Private $routes variable
         */
        public function Get_Routes() {
            return $this->routes;
        }
        
        /**
         * @private
         * @param string $request_uri The current request URI.
         */
        public function __construct() {
            $this->routes = [];
        }
        
        /**
         * Adds a new route to be checked.
         *
         * @param   string      $pattern        The regex pattern assigned to our $callback_func
         * @param   callable    $callback_func  Callback that is called if $pattern matches $request_uri_without_get (return true to stop iterating through routes)
         */
        public function Add_Route($pattern, $callback_func) {
            if (is_callable($callback_func)) {
                $this->routes[] = [
                    "pattern" => $pattern,
                    "callback_func" => $callback_func
                ];
                
                return true;
            }
            
            return false;
        }
        
        /**
         * Iterates through our $routes and executes any callbacks that have a pattern that matches the request uri.
         */
        public function Route($request_uri) {
            $request_uri_parts = explode("?", $request_uri);
            $request_uri_without_get = $request_uri_parts[0];
            
            foreach ($this->routes as $route) {
                $matches = [];
                if (preg_match($route["pattern"], $request_uri_without_get, $matches)) {
                    array_shift($matches);
                    
                    if (call_user_func($route["callback_func"], $matches) === true) {
                        break;
                    }
                }
            }
        }
    }
