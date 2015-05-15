<?php

namespace Volan;

class CustomArrayObject extends \ArrayObject
{
    /**
     * Gets key from current array.
     * Excludes _type from final array.
     *
     * @return array
     */
    public function getArrayKeys()
    {
        $returnValue = [];

        foreach (array_keys($this->getArrayCopy()) as $key):
            if ($key != '_type'):
                $returnValue[] = $key;
        endif;
        endforeach;

        return $returnValue;
    }
}
