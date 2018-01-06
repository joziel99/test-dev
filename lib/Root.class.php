<?php
/**
 * Created by PhpStorm.
 * User: Joziel
 * Date: 05/01/2018
 * Time: 16:42
 */

class Root
{
    private static $root_registed = [];

    public static function get($root, $controller){
        self::register('GET', $root, $controller);
    }

    public static function post($root, $controller){
        self::register('POST', $root, $controller);
    }

    public static function delete($root, $controller){
        self::register('DELETE', $root, $controller);
    }

    public static function put($root, $controller){
        self::register('PUT', $root, $controller);
    }

    private static function register($type, $root, $controller){
        if(!isset(self::$root_registed[$type])){
            self::$root_registed[$type] = [];
        }

        self::$root_registed[$type][] = [
            'root' => $root,
            'controller' => $controller
        ];
    }

    public static function run(){
        if(empty(self::$root_registed[$_SERVER['REQUEST_METHOD']]) && empty(self::$root_registed[$_SERVER['REQUEST_URI']])){
            return;
        }
        $segments = explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        foreach(self::$root_registed[$_SERVER['REQUEST_METHOD']] as $root){
            $segments_root = explode('/',$root['root']);
            if(count($segments_root) !== count($segments)){
                continue;
            }

            $run_controller = true;
            foreach($segments as $index => $segment){
                if ($run_controller && strpos($segments_root[$index], "{") === false && $segments_root[$index] !== $segment) {
                    $run_controller = false;
                    break;
                }
            }

            if($run_controller){
                $params = [];
                foreach($segments_root as $index => $segment){
                    if ( strpos($segment, "{") !== false) {

                        preg_match(sprintf('/(%s)(.*?)(%s)/', preg_quote("{"), preg_quote("}")), $segment, $matches);

                        $params[$matches[2]] = $segments[$index];
                    }
                }

                call_user_func_array($root['controller'], $params);
                break;
            }
        }

    }
}