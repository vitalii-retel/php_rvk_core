<?php

namespace core\events;

class EventManager extends \core\common\HasCore {

    const EVENT_BEFORE_ERROR_DISPLAY = "beforeErrorDisplay";
    const EVENT_BEFORE_CONTROLLER_METHOD = "beforeControllerMethod";
    const EVENT_AFTER_CONTROLLER_METHOD = "afterControllerMethod";

    private $registeredEvents;

    function __construct($core) {
        parent::__construct($core);
        $this->registeredEvents = array();
    }

    private function normalizeEventName($eventName) {
        return strtolower($eventName);
    }

    public function on($eventName, callable $event) {
        $eventName = $this->normalizeEventName($eventName);
        if (!array_key_exists($eventName, $this->registeredEvents)) {
            $this->registeredEvents[$eventName] = array();
        }
        array_push($this->registeredEvents[$eventName], $event);
    }

    public function trigger($eventName, $parameters = array()) {
        $result = true;
        $eventNameNormalized = $this->normalizeEventName($eventName);
        if (array_key_exists($eventNameNormalized, $this->registeredEvents)) {
            foreach ($this->registeredEvents[$eventNameNormalized] as $eventItem) {
                $event = new Event();
                $event->setName($eventName);
                $event->setParameters(new \core\common\ArrayClass($parameters));
                $resultItem = call_user_func($eventItem, $event);
                $result = $result && ($resultItem !== false);
            }
        }
        return $result;
    }

}
