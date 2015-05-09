<?php

namespace Volan\Validator;

class required_string extends AbstractValidator
{
    
    public function isRequired() {
        return true;
    }

    public function isValid($arrNode, $schemaNode)
    {
        return false;
    }
}