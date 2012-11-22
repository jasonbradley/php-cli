<?php
namespace PhpCli\Logging;

abstract class PhpCliLog
{
    abstract public function write($msg);
    
    protected function getLogDate()
    {
        $format = 'Y-m-d H:i:s';
        return date($format);
    }
}

