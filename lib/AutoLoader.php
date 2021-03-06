<?php
/**
 * A simple static class to privide a pointer to the autoloader directory to allow
 * for Composer support
 * @author Nick Staffend <nicholas.a.staffend at gmail.com>
 * 
 */
abstract class AutoLoader{
    /*
     * Runs the autoload.php file generated by composer. This is a convenience
     * class provided to easily incorporate open source support libraries.
     */
    public static function load(){
        if (is_file(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
            
        } else {
            $message = "Cannot find autoload.php";
            //throw new Exception($message);
            echo $message;
        }
    }
}

