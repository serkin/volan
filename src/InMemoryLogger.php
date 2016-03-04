<?php

namespace Volan;

use Psr\Log\LoggerInterface;

class InMemoryLogger implements LoggerInterface
{

    /**
     * @var null|resource
     */
    protected $stream = null;

    public function __construct()
    {

        $filename = "php://memory";

        $fp = fopen($filename, "w+b");

        $this->stream = $fp;

    }

    public function info($message, array $context = array()) {
        fwrite($this->stream, (string)$message . "\n");
    }

    public function warning($message, array $context = array()) {
        $this->info('WARNING: ' . $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->info('DEBUG: ' . $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->info('CRITICAL: ' . $message, $context);
    }

    public function emergency($message, array $context = array())
    {
        $this->info('EMERGENCY: ' . $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->info('ERROR: ' . $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->info('ALERT: ' . $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->info('NOTICE: ' . $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->info($message, $context);
    }

    /**
     *
     *
     * @return string
     */
    public function getLog() {

        rewind($this->stream);

        return stream_get_contents($this->stream);
    }

}


// Works

/*

$filename = "php://memory";

$fp = fopen($filename, "w+b");
fwrite($fp, 'hi');

fwrite($fp, 'dude');
rewind($fp);
var_dump(stream_get_contents($fp));
fwrite($fp, 'dude');
rewind($fp);
var_dump(stream_get_contents($fp));
*/