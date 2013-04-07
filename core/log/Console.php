<?php

namespace core\log;

class Console extends Log {

    private $level;

    protected function __construct() { }
    private function __clone() { }

    public static function getInstance() {
        static $instance;
        if ($instance == null) {
            $instance = new Console();
            $instance->setLevel(LogItem::INFO);
            /* set start time label */
            if (array_key_exists('REQUEST_TIME_FLOAT', $_SERVER)) {
                $instance->addMessage(T::CONSOLE_REQUEST_TIME, LogItem::INFO, '0.' . implode(' ', array_reverse(explode('.', $_SERVER['REQUEST_TIME_FLOAT']))));
            }
            $instance->addMessage(T::CONSOLE_SCRIPT_START, LogItem::INFO, TIME_START);
        }
        return $instance;
    }

    public function dump() {
        echo '<dl class="rvk-dump-box">';
        $start = null;
        $last = null;
        foreach ($this->getMessages() as $key => $value) {
            if ($value->getType() <= $this->getLevel()) {
                list($us, $s) = explode(' ', $value->getMicroTime());
                if ($start == null) {
                    $start = $value->getMicroTime();
                }
                $diff = $this->findMicroTimeDifference($start, $value->getMicroTime());

                echo '<dt>';

                echo '[';
                switch ($value->getType()) {
                    case LogItem::ERROR:
                        echo \core\T::TEXT_LOG_ITEM_ERROR;
                        break;
                    case LogItem::WARNING:
                        echo \core\T::TEXT_LOG_ITEM_WARNING;
                        break;
                    case LogItem::DEBUG:
                        echo \core\T::TEXT_LOG_ITEM_DEBUG;
                        break;
                    case LogItem::INFO:
                        echo \core\T::TEXT_LOG_ITEM_INFO;
                        break;
                    default:
                        echo \core\T::TEXT_LOG_ITEM_UNKNOWN;
                }
                echo '] ';

                echo date($this->getTimeFormat(), $s) , ' ', ' <b>(', ((float)$diff >= 0 ? '+' : '-'), $diff, ' ', \core\T::TEXT_CONSOLE_US;
                if ($last != null) {
                    $diff = $this->findMicroTimeDifference($last, $value->getMicroTime());
                    echo ', ', ((float)$diff >= 0 ? '+' : '-'), $diff, ' ', \core\T::TEXT_CONSOLE_US, ' ', \core\T::TEXT_CONSOLE_FROM_PREV;
                }
                echo ')</b>';

                echo '</dt>';
                echo '<dd>';
                echo $value->getMessage();
                echo '</dd>';

                $last = $value->getMicroTime();
            }
        }
        echo '</dl>';
    }

    public function setLevel($level) {
        $this->level = LogItem::checkType($level);
    }

    public function getLevel() {
        return $this->level;
    }

}
