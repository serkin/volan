<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class NestedArrayValidator extends arrayValidator
{
    /**
     * @return bool
     */
    public function isNested()
    {
        return true;
    }
}
