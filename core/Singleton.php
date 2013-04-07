<?php

namespace core;

class Singleton {

    private static $instances;

    public static function getInstance() {
        if (self::$instances == null) {
            self::$instances = array();
        }
        $className = get_called_class();
        if (!array_key_exists($className, self::$instances)) {
            self::$instances[$className] = new $className();
        }
        return self::$instances[$className];
    }

    protected function __construct() { }
    private function __clone() { }

}
