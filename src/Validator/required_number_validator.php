<?php

/*
 *  @author Serkin Akexander <serkin.alexander@gmail.com>
 */

namespace Volan\Validator;

class array_validator extends AbstractValidator
{
    /**
     * @param \Volan\CustomArrayObject $nodeSchema
     * @param mixed                    $nodeData
     *
     * @return bool
     */
    public function isValid(\Volan\CustomArrayObject $nodeSchema, $nodeData)
    {
        $returnValue = true;

        if (!is_array($nodeData)):
            $returnValue = false;
        endif;

        return $returnValue;
    }
}
