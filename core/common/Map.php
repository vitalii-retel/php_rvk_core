<?php

namespace core\common;

class Map {

    private $data;

    function __construct(array $data = array()) {
        $this->data = array();
        $this->setValues($data);
    }

    private function _normalizeKeyName($key) {
        return strtolower($key);
    }

    public function isExists($key) {
        $key = $this->_normalizeKeyName($key);
        return array_key_exists($key, $this->data);
    }

    public function getValue($key) {
        $key = $this->_normalizeKeyName($key);
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function getValueOr($key, $default = null) {
        $key = $this->_normalizeKeyName($key);
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function getValues() {
        return $this->data;
    }

    public function setValue($key, $value) {
        $key = $this->_normalizeKeyName($key);
        $this->data[$key] = $value;
    }

    public function setValues(array $data) {
        foreach ($data as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    public function merge(Map $a) {
        $this->setValues($a->getValues());
    }

}