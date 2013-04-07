<?php

namespace core\common;

class ArrayClass {

    private $data;
    private $recursive;

    function __construct(array $data = array(), $recursive = false) {
        $this->data = $data;
        $this->recursive = $recursive;
    }

    public function __get($key) {
        return array_key_exists($key, $this->data) ? ($this->recursive && is_array($this->data[$key]) ? new ArrayClass($this->data[$key], true) : $this->data[$key]) : null;
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __call($name, $arguments = array()) {
        if (strpos($name, 'get') === 0 && strlen($name) > 3) {
            $property = lcfirst(substr($name, 3));
            return $this->$property;
        } else if (strpos($name, 'set') === 0 && strlen($name) > 3 && count($arguments) == 1) {
            $property = lcfirst(substr($name, 3));
            $this->$property = $arguments[0];
        } else {
            Env::warning(sprintf(T::WARNING_ARRAY_CLASS_METHOD_NOT_EXISTS, $name));
        }
    }

    public function export() {
        return $this->data;
    }

}
