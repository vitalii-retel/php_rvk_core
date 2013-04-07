<?php

namespace core\log;

class Log {

    private $messages = array();
    private $timeFormat = 'Y-d-m H:i:s';

    public function addMessage($message, $type = LogItem::INFO, $microTime = NULL) {
        $this->messages[] = new LogItem($message, $type, $microTime);
    }

    public function getMessages() {
        return $this->messages;
    }

    public function setTimeFormat($timeFormat) {
        $this->timeFormat = $timeFormat;
    }

    public function getTimeFormat() {
        return $this->timeFormat;
    }

    public function isEmpty() {
        return count($this->getMessages()) <= 0;
    }

    /**
     * @param string $t1
     * @param string $t2
     * @return string difference in seconds.microseconds
     */
    protected function findMicroTimeDifference($t1, $t2) {
        list($us1, $s1) = explode(' ', $t1);
        list($us2, $s2) = explode(' ', $t2);
        $usd = ((float)$s2 - (float)$s1) + ((float)$us2 - (float)$us1);
        return number_format($usd, 6);
    }

}
