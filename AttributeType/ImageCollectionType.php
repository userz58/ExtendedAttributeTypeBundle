<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;

/**
 * Image collection attribute type
 */
class ImageCollectionType extends AbstractAttributeType
{
    /** @var string List separator for flat format */
    const FLAT_SEPARATOR = ',';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return AttributeTypes::IMAGE_COLLECTION;
    }
}
