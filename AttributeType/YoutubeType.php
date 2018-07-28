<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;

/**
 * Youtube video attribute type
 */
class YoutubeType extends AbstractAttributeType
{
    /** @var string link for flat format */
    const LINK = 'https://youtu.be/';
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return AttributeTypes::YOUTUBE;
    }
}
