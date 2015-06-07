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
