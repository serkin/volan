<?php

namespace Volan\Validator;

class string_validator extends AbstractValidator
{

    public function isValid($schemaNode, $arrNode)
    {

        $returnValue = false;
        
        if(!empty($arrNode) && is_string($arrNode)):
            $returnValue = true;
        endif;

        return $returnValue;
    }
}