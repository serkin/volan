<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class RequiredStringValidator extends StringValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
