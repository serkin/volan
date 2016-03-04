<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Traits;

trait ErrorHandlerTrait
{

    /**
     * @var array
     */
    private $error;

    /**
     * @return array
     */
    public function getErrorInfo()
    {
        return $this->error;
    }

    abstract public function getCurrentNode();


    /**
     * @param string $message
     * @param int    $code
     */
    private function setError($message, $code)
    {
        $this->error = [
            'code'  => $code,
            'error' => $message,
            'node'  => $this->getCurrentNode()
                ];
    }
}
