<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Traits;

use Psr\Log\LoggerInterface;

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
     * @return \Psr\Log\LoggerInterface
     */
    private function getLogger()
    {
        return $this->logger;
    }
}
