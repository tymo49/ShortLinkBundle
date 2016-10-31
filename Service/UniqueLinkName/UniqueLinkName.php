<?php

namespace tymo49\ShortlinkBundle\Service\UniqueLinkName;

use tymo49\ShortlinkBundle\Service\StringGenerator\StringGeneratorInterface;

class UniqueLinkName implements UniqueLinkNameInterface
{

    private $em;
    private $generator;
    private $config_data;

    public function __construct($em,StringGeneratorInterface $generator,$config_data)
    {
        $this->em = $em;
        $this->generator = $generator;
        $this->config_data = $config_data;
    }

    public function getUniqueLinkName()
    {
        $k=0;
        $long=$this->config_data['startlong'];
        $tryingGeneration = $this->config_data['tryingGeneration'];
        $linkName = null;

        while(is_null($linkName))
        {
            $linkName = $this->generator->generateString($long);
            $linkNameExist = $this->em->getRepository('tymo49\ShortlinkBundle\Entity\Link')->checkNewLinkName($linkName);
            if(!empty($linkNameExist))
            {
                $linkName = null;
            }
            if(0 == $k%$tryingGeneration)
            {
                $long++;
            }
            $k++;
        }


        return $linkName;

    }


}