<?php

class Autoloader {

    /**
     * Registers Autoloader as an SPL autoloader.
     *
     * @param Bool $prepend 							- Whether to prepend the autoloader or not.
     **/
    public static function register($prepend = false) {

        spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);

    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class 							- A class name.
     **/
    public static function autoload($class) {

		$file = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $include_path = __DIR__ .'/Adestra/'. $file . '.php';
    $include_path = str_replace('Adestra/Adestra', "Adestra", $include_path);

		include_once $include_path;

    }

}
