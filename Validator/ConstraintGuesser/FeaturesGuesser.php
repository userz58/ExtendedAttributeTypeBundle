<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Pim\Component\Catalog\Validator\ChainedAttributeConstraintGuesser;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class FeaturesGuesser implements ConstraintGuesserInterface
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
        $guesser = new ChainedAttributeConstraintGuesser();
        $guesser->addConstraintGuesser(new CountGuesser());
        $guesser->addConstraintGuesser(new FeatureGuesser());

        return $guesser->guessConstraints($attribute);
    }
}
