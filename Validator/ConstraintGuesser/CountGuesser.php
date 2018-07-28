<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class CountGuesser implements ConstraintGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return $attribute->getType() === AttributeTypes::FEATURES;
    }

    /**
     * {@inheritdoc}
     */
    public function guessConstraints(AttributeInterface $attribute)
    {
        $constraints = [];
        $options = [];

        if(is_integer($attribute->getNumberMin())) {
            $options['min'] = $attribute->getNumberMin();
        }

        if(is_integer($attribute->getNumberMax())) {
            $options['max'] = $attribute->getNumberMax();
        }

        if (!empty($options)) {
            $constraints[] = new Assert\Count($options);
        }

        return $constraints;
    }
}
