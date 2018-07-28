<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\ConstraintGuesser;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Validator\ConstraintGuesserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints\FeatureFile;
//use U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints\FeatureNotBlank;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;

class FeatureGuesser implements ConstraintGuesserInterface
{
    /** @staticvar int */
    const TITLE_FIELD_LENGTH = 100;

    /** @staticvar int */
    const DESCRIPTION_FIELD_LENGTH = 255;

    /** @staticvar string */
    const MEGABYTE_UNIT = 'M';

    /** @staticvar string */
    const KILOBYTE_UNIT = 'k';

    /** @staticvar string */
    const KILOBYTE_MULTIPLIER = 1024;

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
        return [
            new Assert\All([
                'constraints' => [
                    new Assert\Type(['type' => 'U58\Bundle\ExtendedAttributeTypeBundle\Model\Feature']),
                    new FeatureFile($this->getFileOptions($attribute)),
                    // Todo: rewrite
                    //new FeaturesListItemNotBlank($this->getNotBlankOptions($attribute)),
                    //
                ]
            ])
        ];
    }

    /**
     * @param $attribute
     *
     * @return array
     */
    protected function getFileOptions($attribute)
    {
        $options = [];

        if ($maxSize = $attribute->getMaxFileSize()) {
            if ($maxSize == (int)$maxSize) {
                $maxSize = (int)$maxSize;
                $unit = self::MEGABYTE_UNIT;
            } else {
                $maxSize = intval($maxSize * self::KILOBYTE_MULTIPLIER);
                $unit = self::KILOBYTE_UNIT;
            }
            if ($maxSize > 0) {
                $options['maxSize'] = sprintf('%d%s', $maxSize, $unit);
            }
        }

        if ($allowedExtensions = $attribute->getAllowedExtensions()) {
            $options['allowedExtensions'] = $allowedExtensions;
        }

        return $options;
    }

    /**
     * @param $attribute
     *
     * @return array
     */
    protected function getNotBlankOptions($attribute)
    {
        $options = [];

        $options['titleLength'] = self::TITLE_FIELD_LENGTH;
        if ($maxCharacters = $attribute->getMaxCharacters()) {
            $options['titleLength'] = min($maxCharacters,self::TITLE_FIELD_LENGTH);
        }

        $options['descriptionLength'] = self::DESCRIPTION_FIELD_LENGTH;

        return $options;
    }
}
