<?php

namespace Volan\Validator;

class array_of_strings_validator extends AbstractValidator
{
    /**
     * @param mixed $nodeData
     *
     * @return bool
     */
    public function isValid($nodeData)
    {

        foreach($nodeData as $value) {
            if (!is_string($value)) {
                return false;
            }
        }

        return true;
    }
}
