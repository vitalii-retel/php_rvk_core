<?php

namespace core\log;

class LogItem {
    
    const ERROR = 0;
    const WARNING = 1;
    const DEBUG = 2;
    const INFO = 3;

    private $microTime;
    private $type;
    private $message;

    function __construct($message, $type = self::INFO, $microTime = null) {
        if ($microTime == null)
            $microTime = microtime();
        $this->setMicroTime($microTime);
        $this->setType($type);
        $this->setMessage($message);
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMicroTime($microTime) {
        $this->microTime = $microTime;
    }

    public function getMicroTime() {
        return $this->microTime;
    }

    public function setType($type) {
        $this->type = self::checkType($type);
    }

    public function getType() {
        return $this->type;
    }

    static public function checkType($type) {
        if (!in_array($type, array(self::ERROR, self::WARNING, self::DEBUG, self::INFO)))
            $type = self::INFO;
        return $type;
    }
}
