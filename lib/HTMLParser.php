<?php
abstract class HTMLParser{
    /**
     * This is a list printer for the web pages. Allows for less code repetition
     * for commonly accessed functionality.
     * @param type $devices An array of devices to print
     * @param type $URL The root URL provided for the href
     * @return string
     */
    public static function printDevices($devices, $URL){
        $result = "";
        $count = 1;
        foreach($devices as $device){
            $result .= "<a href=".$URL."?id=".$device->getId().">".$count++.". ".$device->getName()."</a><br />";
        }
        if(count == 1){
            return "<none>";
        }
        return $result;
    }
}