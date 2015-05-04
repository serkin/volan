<?php

namespace Volan;

class Volan
{

    private $schema;


    public function __construct($schema) {
        $this->schema = $schema;
    }

    public function validate($arr)
    {
        
        foreach ($arr as $node):
            if(empty($node['_type']) or class_exists($node['_type'], true)):
                return 0;
            endif;
            
            $nodeTypeClassString = "\Volan\Validator\\" . $node['_type'];
            
            $nodeTypeClass = new $nodeTypeClassString;
            
            unset($node['_type']);
            $keys = array_keys($node);
        endforeach;

    }

}