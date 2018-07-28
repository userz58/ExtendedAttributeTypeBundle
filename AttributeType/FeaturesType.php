<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;

/**
 * Features list attribute type
 */
class FeaturesType extends AbstractAttributeType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return AttributeTypes::FEATURES;
    }
}
