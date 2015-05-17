<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class string_validator extends AbstractValidator
{
    /**
     * Validate wether given data is string.
     *
     * @param \Volan\CustomArrayObject $nodeSchema
     * @param mixed                    $nodeData
     *
     * @return bool
     */
    public function isValid(\Volan\CustomArrayObject $nodeSchema, $nodeData)
    {
        $returnValue = false;

        if (!empty($nodeData) && is_string($nodeData)):
            $returnValue = true;
        endif;

        return $returnValue;
    }
}
