<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class requiredArrayValidator extends arrayValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
