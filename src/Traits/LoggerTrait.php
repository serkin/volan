<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Traits;

use Psr\Log\LoggerInterface;
use Volan\InMemoryLogger;

trait LoggerTrait
{


    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;


    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }



    /**
     * Gets current logger
     *
     * @return InMemoryLogger
     */
    private function getLogger()
    {
        return $this->logger;
    }
}
