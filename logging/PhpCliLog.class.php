<?php

abstract class PhpCliLog
{
    abstract public function write($msg);
    
    protected function getLogDate()
    {
        $format = 'Y-m-d H:i:s';
        return date($format);
    }
}

class PhpCliLogException extends Exception
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
