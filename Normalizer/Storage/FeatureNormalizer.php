<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Storage;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\FeatureInterface;

class FeatureNormalizer implements NormalizerInterface
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
    public function normalize($object, $format = null, array $context = [])
    {
        return $this->stdNormalizer->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FeatureInterface && 'storage' === $format;
    }
}
