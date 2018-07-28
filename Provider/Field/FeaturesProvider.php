<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Provider\Field;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Bundle\EnrichBundle\Provider\Field\FieldProviderInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class FeaturesProvider implements FieldProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getField($element)
    {
        return AttributeTypes::FEATURES;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($element)
    {
        return $element instanceof AttributeInterface
            && AttributeTypes::FEATURES === $element->getType();
    }
}
