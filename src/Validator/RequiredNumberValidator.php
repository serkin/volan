<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class RequiredNumberValidator extends NumberValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
