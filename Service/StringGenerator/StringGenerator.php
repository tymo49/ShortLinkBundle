<?php

namespace tymo49\ShortlinkBundle\Service\StringGenerator;

class StringGenerator implements StringGeneratorInterface
{

    public function generateString($long)
    {
        $charTable = array_merge(range('a', 'z'), range(0,9));
        $sizeOfCharTabl = count($charTable);
        $string = '';

        while(strlen($string) < $long)
        {
            $string .= $charTable[mt_rand(0, $sizeOfCharTabl - 1)];
        }
        return $string;
    }


}