<?php

namespace acurrieclark\IonicPhpPusher\Exception;

/**
 * Class PusherException
 *
 * @package acurrieclark\IonicPhpPusher\Exception
 */

class PusherException extends \Exception
{
  private $response;

  function __construct($message, $code = 0, $previous = NULL, $response = false) {
    $this->response = $response;
    parent::__construct($message, $code, $previous);
  }

  public function getType($full = false) {
    $class = get_class($this);
    $classExploded = explode("\\",$class);
    return ($full) ? $class : end($classExploded);
  }

  public function hasResponse() {
    return ($this->response !== false) ? true : false;
  }

  public function getResponse() {
    return $this->response;
  }

}
