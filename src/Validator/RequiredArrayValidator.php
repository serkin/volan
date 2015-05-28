<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class RequiredArrayValidator extends ArrayValidator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
