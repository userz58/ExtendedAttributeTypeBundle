<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class CountryGuesser implements ConstraintGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return $attribute->getType() === AttributeTypes::COUNTRY;
    }

    /**
     * {@inheritdoc}
     */
    public function guessConstraints(AttributeInterface $attribute)
    {
        return [
            new Assert\Country()
        ];
    }
}
