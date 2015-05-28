<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class requiredBooleanValidator extends booleanValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
