<?php

namespace core\common;

class HasCore {

    private $core;

    function __construct($core) {
        $this->setCore($core);
    }

    protected function setCore($core) {
        $this->core = $core;
    }

    public function getCore() {
        return $this->core;
    }
}
