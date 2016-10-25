<?php

namespace legomolina\SimpleORM\Exception;


class InvalidORMArgument extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class InvalidORMMethod extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}