<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesser\FileGuesser as PimFileGuesser;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class FileGuesser extends PimFileGuesser
{
    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return $attribute->getType() === AttributeTypes::IMAGE_COLLECTION;
    }
}
