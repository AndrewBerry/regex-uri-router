<?php
    include_once("../vendor/autoload.php");
    include_once("../src/regex-uri-router.php");

    class Router_Test extends PHPUnit_Framework_TestCase {
        
        public function test_Adding_Routes() {
            $router = new AndrewBerry\Regex_URI_Router();
            
            function example_callback() {
            }
            
            $initial_count = count($router->Get_Routes());
            
            $this->assertEquals($router->Add_Route("/^\/$/", "example_callback"), true);
            $this->assertEquals($router->Add_Route("/^\/$/", "not_a_function"), false);
            
            $this->assertEquals(count($router->Get_Routes()), $initial_count + 1);
        }
        
        public function test_Roll_Through_Routes() {
            $router = new AndrewBerry\Regex_URI_Router();
            
            
            $router->Add_Route("/^\//", function($args) {
                global $output;
                echo "pre";
                return false;
            });
            
            $router->Add_Route("/^\/home\/$/", function($args) {
                global $output;
                echo "home";
                return true;
            });
            
            $router->Add_Route("/^\/.*\/?$/", function($args) {
                global $output;
                echo "post";
                return true;
            });
            
            ob_start();
            $router->Route("/home/");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("prehome", $output);
            
            ob_start();
            $router->Route("/intentional-post/");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("prepost", $output);
        }
        
        public function test_Routing() {
            $router = new AndrewBerry\Regex_URI_Router();
            
            ob_start();
            
            $router->Add_Route("/^\/add\/(\d+)\/(\d+)\/$/", function($args) {
                echo $args[0] + $args[1];
                return true;
            });
            $router->Add_Route("/^\/$/", function($args) {
                echo "home";
                return true;
            });
            $router->Add_Route("/^.*$/", function($args) {
                echo "404";
                return true;
            });
            
            $router->Route("/add/3/4/");
            $router->Route("/");
            $router->Route("/intentional-404/");
            
            $output = ob_get_contents();
            ob_end_clean();

            $this->assertEquals("7home404", $output);
        }
    }
