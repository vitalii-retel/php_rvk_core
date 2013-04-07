<?php

namespace core;

class AutoLoad extends Singleton {

    private $paths;

    protected function __construct() {
        parent::__construct();
        $this->paths = array(
            __DIR__
        );
    }

    public function __invoke($className) {
        foreach ($this->getPaths() as $path) {
            $classFileName = $path . '/' . str_replace('\\', '/', $className) . '.php';
            if (file_exists($classFileName)) {
                require $classFileName;
                break;
            }
        }
    }

    public function addPath($path) {
        $path = rtrim($path, '/');
        if (array_search($path, $this->paths) === false) {
            if (is_dir($path)) {
                $this->paths[] = $path;
            } else {
                trigger_error("Not existing directory: \"{$path}\"", E_USER_WARNING);
            }
        }
    }

    public function setPaths($paths) {
        $this->paths = $paths;
    }

    public function getPaths() {
        return $this->paths;
    }


}
