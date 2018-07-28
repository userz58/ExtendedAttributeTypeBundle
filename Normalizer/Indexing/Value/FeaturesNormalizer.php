<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Normalizer\Indexing\Value;

use Pim\Component\Catalog\Model\ValueInterface;
use Pim\Component\Catalog\Normalizer\Indexing\Product\ProductNormalizer;
use Pim\Component\Catalog\Normalizer\Indexing\ProductAndProductModel;
use Pim\Component\Catalog\Normalizer\Indexing\ProductModel;
use Pim\Component\Catalog\Normalizer\Indexing\Value\AbstractProductValueNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Value\FeaturesValue;

class FeaturesNormalizer extends AbstractProductValueNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FeaturesValue && (
                $format === ProductNormalizer::INDEXING_FORMAT_PRODUCT_INDEX ||
                $format === ProductModel\ProductModelNormalizer::INDEXING_FORMAT_PRODUCT_MODEL_INDEX ||
                $format === ProductAndProductModel\ProductModelNormalizer::INDEXING_FORMAT_PRODUCT_AND_MODEL_INDEX
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function getNormalizedData(ValueInterface $value)
    {
        $indexedCollection = [];
        $features = $value->getData();

        if (null === $features) {
            return null;
        }

        foreach ($features as $feature) {
            $indexedCollection[] = [
                'title'       => $feature->getTitle(),
                'description' => $feature->getDescription()
            ];
        }

        return $indexedCollection;
    }
}
