<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class required_number_validator extends number_validator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
