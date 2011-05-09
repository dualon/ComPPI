<?php

namespace Comppi\LoaderBundle\Service\DatabaseParser\Parser;

class Ptarget extends AbstractParser implements ParserInterface
{
    public function isMatch($filename) {
        return ('ptarget' == substr($filename, 0, 7));
    }
    
    public function getFieldArray($file_handle) {
        /** @todo improve field names */
        $fields = array(
            'name',
            'localization',
            'weight'
        );
        
        return $fields;
    }
}