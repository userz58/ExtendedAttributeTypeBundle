<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;

/**
 * Country attribute type
 */
class CountryType extends AbstractAttributeType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return AttributeTypes::COUNTRY;
    }
}
