<?php

namespace Volan;

class DummyLogger extends \Monolog\Logger
{



    public function warning($message, array $context = array()){return;}

    public function info($message, array $context = array()){return;}



}