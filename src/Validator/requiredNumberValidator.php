<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class requiredNumberValidator extends numberValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
