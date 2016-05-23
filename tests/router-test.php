<?php
    include_once("../src/Regex_URI_Router.php");

    class Router_Test extends PHPUnit_Framework_TestCase {
        
        public function test_Roll_Through_Routes() {
            
            function Pre_False($args) {
                echo "pre";
                return false;
            }
            
            function Home_True($args) {
                echo "home";
                return true;
            }
            
            function Post_True($args) {
                echo "post";
                return true;
            }
            
            ob_start();
            $router = new AndrewBerry\Regex_URI_Router("/home/");
            $router->Test("/^\//", "Pre_False");
            $router->Test("/^\/home\/$/", "Home_True");
            $router->Test("/^\/.*\/?$/", "Post_True");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("prehome", $output);

            ob_start();
            $router = new AndrewBerry\Regex_URI_Router("/intentional-post/");
            $router->Test("/^\//", "Pre_False");
            $router->Test("/^\/home\/$/", "Home_True");
            $router->Test("/^\/.*\/?$/", "Post_True");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("prepost", $output);
        }
        
        public function test_Routing() {
            
            function Route_Add($args) {
                echo $args[0] + $args[1];
                return true;
            }
            
            function Route_Home($args) {
                echo "home";
                return true;
            }
            
            function Route_404($args) {
                echo "404";
                return true;
            }
            
            ob_start();
            $router = new AndrewBerry\Regex_URI_Router("/add/3/4/");
            $router->Test("/^\/add\/(\d+)\/(\d+)\/$/", "Route_Add");
            $router->Test("/^\/$/", "Route_Home");
            $router->Test("/^.*$/", "Route_404");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("7", $output);
            
            ob_start();
            $router = new AndrewBerry\Regex_URI_Router("/");
            $router->Test("/^\/add\/(\d+)\/(\d+)\/$/", "Route_Add");
            $router->Test("/^\/$/", "Route_Home");
            $router->Test("/^.*$/", "Route_404");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("home", $output);
            
            ob_start();
            $router = new AndrewBerry\Regex_URI_Router("/intentional-404/");
            $router->Test("/^\/add\/(\d+)\/(\d+)\/$/", "Route_Add");
            $router->Test("/^\/$/", "Route_Home");
            $router->Test("/^.*$/", "Route_404");
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertEquals("404", $output);
        }
    }
