<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Flat;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\VersioningBundle\Normalizer\Flat\AbstractValueDataNormalizer;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\FeatureInterface;

class FeatureNormalizer extends AbstractValueDataNormalizer
{
    /** @var NormalizerInterface */
    protected $stdNormalizer;

    /** @var string[] */
    protected $supportedFormats = ['flat'];

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
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FeatureInterface && in_array($format, $this->supportedFormats);
    }

    /**
     * {@inheritdoc}
     */
    protected function doNormalize($object, $format = null, array $context = [])
    {
        return json_encode($this->stdNormalizer->normalize($object, $format, $context), JSON_UNESCAPED_UNICODE);
    }
}
