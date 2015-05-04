<?php

namespace Volan\Validator;


abstract class AbstractValidator
{
    
    public function isRequired()
    {
        return false;
    }
    
    abstract function isValid($arrNode, $schemaNode);
    
    
}