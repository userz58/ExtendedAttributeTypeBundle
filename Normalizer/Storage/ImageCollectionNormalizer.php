<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Storage;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\ImageCollectionInterface;

class ImageCollectionNormalizer implements NormalizerInterface
{
    /** @var NormalizerInterface */
    protected $stdNormalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->stdNormalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($collection, $format = null, array $context = [])
    {
        return $this->stdNormalizer->normalize($collection, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ImageCollectionInterface && 'storage' === $format;
    }
}
