<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\ArrayConverter\FlatToStandard\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\FlatToStandard\Product\ValueConverter\ValueConverterInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\ImageCollectionType;

/**
 * Converts a image collection value from Akeneo PIM flat format to Akeneo PIM standard format
 *
 * Here is an example with the temperature attribute.
 * Flat format:
 * [
 *      'my-image-collection' => "/asdas/a/c/d/image1.jpg,/asdes/d/c/d/image2.jpg,/ashsv/m/c/l/image3.jpg"
 * ]
 *
 * Standard format:
 * [
 *      'my-image-collection' => [{
 *          "locale": "en_US",
 *          "scope": null,
 *          "data": [
 *              "/asdas/a/c/d/image1.jpg",
 *              "/asdes/d/c/d/image2.jpg",
 *              "/ashsv/m/c/l/image3.jpg"
 *          ]
 *      }]
 * ]
 */
class ImageCollectionConverter implements ValueConverterInterface
{
    /** @var string[] */
    protected $supportedFieldTypes;

    /**
     * @param string[] $supportedFieldTypes
     */
    public function __construct(array $supportedFieldTypes)
    {
        $this->supportedFieldTypes = $supportedFieldTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsField($attributeType)
    {
        return in_array($attributeType, $this->supportedFieldTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function convert(array $attributeFieldInfo, $value)
    {
        if ('' === trim($value)) {
            return [];
        }

        return [
            $attributeFieldInfo['attribute']->getCode() => [[
                'locale' => $attributeFieldInfo['locale_code'],
                'scope'  => $attributeFieldInfo['scope_code'],
                'data'   => explode(ImageCollectionType::FLAT_SEPARATOR, $value),
            ]],
        ];
    }
}
