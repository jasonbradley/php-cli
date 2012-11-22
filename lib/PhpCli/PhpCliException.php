<?php
namespace PhpCli;

class PhpCliException extends \Exception
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
