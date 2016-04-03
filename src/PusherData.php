<?php

namespace acurrieclark\IonicPhpPusher;

/**
* Pusher
*/
class PusherData
{

  public $android, $ios, $template_defaults;

  function __construct()
  {
    $this->android = new \stdClass;
    $this->ios = new \stdClass;
    $this->android->data = new \stdClass;
    $this->android->payload = new \stdClass;
    $this->ios->payload = new \stdClass;
    $this->template_defaults = new \stdClass;
  }

}


?>
