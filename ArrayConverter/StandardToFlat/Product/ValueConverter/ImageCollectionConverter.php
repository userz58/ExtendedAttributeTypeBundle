<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\ArrayConverter\StandardToFlat\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\AbstractValueConverter;
use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\ValueConverterInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\ImageCollectionType;

/**
 * Converts a image collection value from Akeneo PIM standard format to Akeneo PIM flat format
 *
 * Standard format:
 * [
 *      'my-image-collection' => [{
 *          "locale": "en_US",
 *          "scope": null,
 *          "data": [
 *              "/asdas/a/c/d/image1.jpg",
 *              "/asdes/d/c/k/image2.jpg",
 *              "/ashsv/m/c/l/image3.jpg"
 *          ]
 *      }]
 * ]
 *
 * Here is an example with the temperature attribute.
 * Flat format:
 * [
  *      'my-image-collection' => "/asdas/a/c/d/image1.jpg,/asdes/d/c/d/image2.jpg,/ashsv/m/c/l/image3.jpg"
 * ]
 */
class ImageCollectionConverter extends AbstractValueConverter implements ValueConverterInterface
{
    /**
     * Converts a value
     *
     * @param string $attributeCode
     * @param mixed  $data
     *
     * @return array
     */
    public function convert($attributeCode, $data)
    {
        $convertedItem = [];

        foreach ($data as $value) {
            $flatName = $this->columnsResolver->resolveFlatAttributeName(
                $attributeCode,
                $value['locale'],
                $value['scope']
            );

            $arrayValues = !empty($value['data']) ? $value['data'] : [];
            $convertedItem[$flatName] = implode(ImageCollectionType::FLAT_SEPARATOR, $arrayValues);
        }

        return $convertedItem;
    }
}
