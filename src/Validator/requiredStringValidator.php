<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class requiredStringValidator extends stringValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
