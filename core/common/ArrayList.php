<?php

namespace core\common;

class ArrayList {

    private $data;

    function __construct(array $data = array()) {
        $this->data = $data;
    }

    public function addItems(array $items) {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem($item) {
        $this->data[] = $item;
    }

    public function getItems() {
        return $this->data;
    }

    public function isEmpty() {
        return count($this->data) <= 0;
    }

    public function pushItem($item) {
        array_push($this->data, $item);
    }

    public function popItem() {
        return array_pop($this->data);
    }

    public function unShiftItem($item) {
        array_unshift($this->data, $item);
    }

    public function shiftItem() {
        return array_shift($this->data);
    }


}
