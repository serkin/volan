<?php

namespace Volan\Validator;

class required_array_validator extends array_validator
{
    /**
     * @return bool
     */
    public function isRequired()
    {
        return true;
    }
}
