<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\ArrayConverter\FlatToStandard\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\FlatToStandard\Product\ValueConverter\ValueConverterInterface;

class FeaturesConverter implements ValueConverterInterface
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
                'data'   => json_decode($value, true),
            ]],
        ];
    }
}
