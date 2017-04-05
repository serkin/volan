<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class RequiredNestedArrayValidator extends NestedArrayValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
