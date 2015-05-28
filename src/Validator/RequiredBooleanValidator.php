<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class RequiredBooleanValidator extends BooleanValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
