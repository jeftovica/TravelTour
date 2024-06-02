<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Config {
    public static function DB_NAME() {
        return Config::get_env("DB_NAME", "travel_tour");
    }
    public static function DB_PORT() {
        return Config::get_env("DB_PORT", 3306);
    }
    public static function DB_USER() {
        return Config::get_env("DB_USER", 'root');
    }
    public static function DB_PASSWORD() {
        return Config::get_env("DB_PASSWORD", 'angela01180');
    }
    public static function DB_HOST() {
        return Config::get_env("DB_HOST", '127.0.0.1');
    }
    public static function JWT_SECRET() {
        return Config::get_env("DB_HOST", ',dpPL,Se%fM-UVQBwf/X0T&B!DF6%}');
    }
    public static function get_env($name, $default){
        return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
    }
}

// DB setup

//define('DB_NAME', 'travel_tour');
//define('DB_PORT', 3306);
//define('DB_USER', 'root');
//define('DB_PASSWORD', 'angela01180');
//define('DB_HOST', '127.0.0.1');

//define('JWT_SECRET', ',dpPL,Se%fM-UVQBwf/X0T&B!DF6%}');

