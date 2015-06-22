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

        $returnValue = true;


        if(!is_array($nodeData)) {

            $this->setErrorDescription('Value supposed to be an array');
            $returnValue = false;

        } else {

            foreach ($nodeData as $value) {
                if (!is_string($value)) {
                    $returnValue = false;
                }
            }
        }

        return $returnValue;
    }
}
