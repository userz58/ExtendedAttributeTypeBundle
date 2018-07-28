<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Pim\Component\Catalog\Validator\ChainedAttributeConstraintGuesser;
use Symfony\Component\Validator\Constraints as Assert;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class ImageCollectionGuesser implements ConstraintGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportAttribute(AttributeInterface $attribute)
    {
        return $attribute->getType() === AttributeTypes::IMAGE_COLLECTION;
    }

    /**
     * {@inheritdoc}
     */
    public function guessConstraints(AttributeInterface $attribute)
    {
        $constraints = [];
        $options = [];

        if(null !== $attribute->getNumberMax()) {
            $options['max'] = (int) $attribute->getNumberMax();
        }
        if(null !== $attribute->getNumberMin()) {
            $options['min'] = (int) $attribute->getNumberMin();
        }
        if (!empty($options)) {
            $constraints[] = new Assert\Count($options);
        }

        $constraints[] = $this->addImageConstraints($attribute);

        return $constraints;
    }

    public function addImageConstraints(AttributeInterface $attribute)
    {
        $guesser = new ChainedAttributeConstraintGuesser();
        $guesser->addConstraintGuesser(new FileGuesser());

        $constraints = $guesser->guessConstraints($attribute);
        $constraints[] = new Assert\NotBlank();

        return new Assert\All(['constraints' => $constraints]);
    }
}
