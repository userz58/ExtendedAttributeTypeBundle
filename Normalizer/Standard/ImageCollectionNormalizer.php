<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Standard;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\ImageCollectionInterface;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;

class ImageCollectionNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($collection, $format = null, array $context = [])
    {
        $result = [];

        foreach ($collection as $file) {
            if ($file instanceof FileInfoInterface) {
                $result[] = $file->getKey();
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ImageCollectionInterface && 'standard' === $format;
    }
}
