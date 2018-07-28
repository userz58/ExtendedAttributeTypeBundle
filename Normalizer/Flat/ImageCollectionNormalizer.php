<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Flat;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\VersioningBundle\Normalizer\Flat\AbstractValueDataNormalizer;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\ImageCollectionInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\ImageCollectionType;

/**
 *    before:  $data = ['/absolute/path/a/b/c/file1.jpg', '/absolute/path/a/b/c/file2.jpg', '/absolute/path/a/b/c/file3.jpg']
 *    after: $data = '/absolute/path/a/b/c/file1.jpg,/absolute/path/a/b/c/file2.jpg,/absolute/path/a/b/c/file3.jpg'
 */
class ImageCollectionNormalizer extends AbstractValueDataNormalizer
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
        return $data instanceof ImageCollectionInterface && in_array($format, $this->supportedFormats);
    }

    /**
     * {@inheritdoc}
     */
    protected function doNormalize($collection, $format = null, array $context = [])
    {
        $result = $this->stdNormalizer->normalize($collection, $format, $context);

        return implode(ImageCollectionType::FLAT_SEPARATOR, $result);
    }
}
