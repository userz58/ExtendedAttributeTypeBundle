<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\ArrayConverter\StandardToFlat\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\AbstractValueConverter;
use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\ValueConverterInterface;

class FeaturesConverter extends AbstractValueConverter implements ValueConverterInterface
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

            $convertedItem[$flatName] = json_encode($value['data'], JSON_UNESCAPED_UNICODE);
        }

        return $convertedItem;
    }
}
