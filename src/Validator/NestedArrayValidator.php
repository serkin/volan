<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class NestedArrayValidator extends ArrayValidator
{
    /**
     * @return bool
     */
    public function isNested()
    {
        return true;
    }
}
