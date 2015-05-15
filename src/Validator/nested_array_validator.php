<?php

namespace Volan\Validator;

class nested_array_validator extends array_validator
{
    /**
     * @return bool
     */
    public function isNested()
    {
        return true;
    }
}
