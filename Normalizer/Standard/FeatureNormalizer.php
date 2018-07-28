<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Standard;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\FeatureInterface;

class FeatureNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'filePath'    => $object->getFileInfo() !== null ? $object->getFileInfo()->getKey() : null,
            'title'       => $object->getTitle(),
            'description' => $object->getDescription()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FeatureInterface && 'standard' === $format;
    }
}
