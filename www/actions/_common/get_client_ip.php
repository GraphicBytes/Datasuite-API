<?php
function getClientIP()
{
    $ipaddress = '';
    switch (true) {
        case isset($_SERVER['HTTP_X_REAL_IP']):
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
            break;
        case isset($_SERVER['HTTP_CLIENT_IP']):
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            break;
        case isset($_SERVER['HTTP_X_FORWARDED_FOR']):
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            break;
        case isset($_SERVER['HTTP_X_FORWARDED']):
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            break;
        case isset($_SERVER['HTTP_FORWARDED_FOR']):
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            break;
        case isset($_SERVER['HTTP_FORWARDED']):
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
            break;
        case isset($_SERVER['REMOTE_ADDR']):
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            break;
        default:
            $ipaddress = null;
    }

    return $ipaddress;
}