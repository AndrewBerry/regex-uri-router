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
         * Destination path to test each route against.
         * 
         * Initially set in __construct() and generally set to $_SERVER["REQUEST_URI"]
         *
         * @private
         * @var string
         */
        private $destination;
        
        /**
         * This flag determines whether or not tests are skipped.
         * 
         * @private
         * @var boolean
         */
        private $skip_future_tests;
        
        /**
         * @private
         * @param string $request_uri The current request URI.
         */
        public function __construct($destination) {
            $this->destination = $destination;
            $this->skip_future_tests = false;
        }
        
        /**
         * Executes $callback_func if $pattern matches $destination AND $skip_future_tests is not true.
         *
         * @param   string      $pattern        The regex pattern assigned to our $callback_func
         * @param   callable    $callback_func  Callback that is called if $pattern matches $destination (return true to skip future tests)
         */
        public function Test($pattern, $callback_func) {
            if ($this->skip_future_tests === true) {
                return false;
            }
            
            if (!is_callable($callback_func)) {
                return false;
            }
            
            $matches = [];
            if (preg_match($pattern, $this->destination, $matches)) {
                array_shift($matches);
                $this->skip_future_tests = call_user_func($callback_func, $matches);
            }
            
            return true;
        }
    }
