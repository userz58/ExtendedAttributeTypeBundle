<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Completeness\Checker;

use U58\Bundle\ExtendedAttributeTypeBundle\AttributeType\AttributeTypes;
use Pim\Component\Catalog\Completeness\Checker\ValueCompleteCheckerInterface;
use Pim\Component\Catalog\Model\ChannelInterface;
use Pim\Component\Catalog\Model\LocaleInterface;
use Pim\Component\Catalog\Model\ValueInterface;

class FeaturesCompleteChecker implements ValueCompleteCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isComplete(
        ValueInterface $value,
        ChannelInterface $channel,
        LocaleInterface $locale
    ) {
        if (null !== $value->getScope() && $channel !== $value->getScope()) {
            return false;
        }
        if (null !== $value->getLocale() && $locale !== $value->getLocale()) {
            return false;
        }

        $collection = $value->getData();
        // Todo: проверить каждую строку на наличие пустых значений
        return null !== $collection && count($collection) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsValue(
        ValueInterface $value,
        ChannelInterface $channel,
        LocaleInterface $locale
    ) {
        return AttributeTypes::BACKEND_TYPE_FEATURES === $value->getAttribute()->getBackendType();
    }
}
